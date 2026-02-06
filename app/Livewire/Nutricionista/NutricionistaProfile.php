<?php

namespace App\Livewire\Nutricionista;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\PersonalData;
use App\Models\NutricionistaSettings;
use App\Notifications\PasswordChangedNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class NutricionistaProfile extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $phone = '';
    public $profile_photo;
    public $profile_photo_path = '';
    public $consultation_price = 30.00;

    public $current_password = '';
    public $password = '';
    public $password_confirmation = '';

    public $hasPersonalData = false;
    public $hasPassword = false;

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:10',
            'profile_photo' => 'nullable|image|max:5120',
            'consultation_price' => 'required|numeric|min:0|max:999999.99',
        ];

        if ($this->hasPassword) {
            $rules['current_password'] = 'required_with:password|string';
        }
        
        $rules['password'] = 'nullable|string|min:8|confirmed';

        return $rules;
    }

    protected $messages = [
        'name.required' => 'El nombre es obligatorio.',
        'name.max' => 'El nombre no puede tener más de 255 caracteres.',
        'phone.max' => 'El teléfono no puede tener más de 10 caracteres.',
        'current_password.required_with' => 'Debes ingresar tu contraseña actual para cambiarla.',
        'password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
        'password.confirmed' => 'Las contraseñas no coinciden.',
    ];

    public function mount()
    {
        $user = Auth::user();
        
        // Verificar que el usuario es nutricionista
        if (!$user->role || $user->role->name !== 'nutricionista') {
            abort(403, 'No tienes acceso a esta página.');
        }

        $this->name = $user->name;
        $this->email = $user->email;
        
        $defaultPassword = \App\Http\Middleware\EnsurePasswordChanged::DEFAULT_PASSWORD;
        $this->hasPassword = !empty($user->password) && !Hash::check($defaultPassword, $user->password);

        if ($user->personalData) {
            $this->hasPersonalData = true;
            $this->phone = $user->personalData->phone ?? '';
            $this->profile_photo_path = $user->personalData->profile_photo ?? '';
        }

        // Cargar precio de consulta
        if ($user->nutricionistaSettings) {
            $this->consultation_price = $user->nutricionistaSettings->consultation_price;
        }
    }

    public function saveProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:10',
            'profile_photo' => 'nullable|image|max:5120',
            'consultation_price' => 'required|numeric|min:0|max:999999.99',
        ]);

        try {
            $user = Auth::user();
            
            $user->update(['name' => $this->name]);

            $photoPath = $this->profile_photo_path;
            if ($this->profile_photo) {
                if ($user->personalData && $user->personalData->profile_photo) {
                    Storage::disk('public')->delete($user->personalData->profile_photo);
                }
                
                $photoPath = $this->profile_photo->store('profile-photos', 'public');
            }

            if ($this->hasPersonalData && $user->personalData) {
                $user->personalData->update([
                    'phone' => $this->phone,
                    'profile_photo' => $photoPath,
                ]);
                
                $this->profile_photo_path = $photoPath;
            } else {
                // Crear datos personales si no existen
                PersonalData::create([
                    'user_id' => $user->id,
                    'phone' => $this->phone,
                    'profile_photo' => $photoPath,
                ]);
                $this->hasPersonalData = true;
                $this->profile_photo_path = $photoPath;
            }

            $this->profile_photo = null;

            // Guardar o actualizar precio de consulta
            NutricionistaSettings::updateOrCreate(
                ['user_id' => $user->id],
                ['consultation_price' => $this->consultation_price]
            );

            return redirect()->to(route('nutricionista.profile'))->with('success', 'Tu perfil ha sido actualizado correctamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al actualizar el perfil: ' . $e->getMessage());
        }
    }

    public function updatePassword()
    {
        $rules = [];
        if ($this->hasPassword) {
            $rules['current_password'] = 'required|string';
        }
        $rules['password'] = 'required|string|min:8|confirmed';

        $this->validate($rules);

        try {
            $user = Auth::user();

            if ($this->hasPassword && !Hash::check($this->current_password, $user->password)) {
                session()->flash('password_error', 'La contraseña actual es incorrecta.');
                return;
            }

            $user->update([
                'password' => Hash::make($this->password)
            ]);

            $user->notify(new PasswordChangedNotification());

            $this->reset(['current_password', 'password', 'password_confirmation']);
            
            $this->hasPassword = true;

            return redirect()->to(route('nutricionista.profile'))->with('password_success', 'Tu contraseña ha sido actualizada correctamente.');
        } catch (\Exception $e) {
            session()->flash('password_error', 'Error al actualizar la contraseña: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.nutricionista.nutricionista-profile');
    }
}
