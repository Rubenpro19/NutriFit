<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\PersonalData;
use App\Notifications\PasswordChangedNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserProfile extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $phone = '';
    public $address = '';
    public $birth_date = '';
    public $gender = '';
    public $age = null;
    public $profile_photo;
    public $profile_photo_path = '';

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
            'address' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date|before:today',
            'profile_photo' => 'nullable|image|max:5120',
        ];

        // Solo validar contraseña actual si el usuario tiene password establecido
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
        'address.max' => 'La dirección no puede tener más de 255 caracteres.',
        'birth_date.date' => 'La fecha de nacimiento debe ser una fecha válida.',
        'birth_date.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
        'current_password.required_with' => 'Debes ingresar tu contraseña actual para cambiarla.',
        'password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
        'password.confirmed' => 'Las contraseñas no coinciden.',
    ];

    public function mount()
    {
        $user = Auth::user();
        
        // Verificar que el usuario es paciente
        if (!$user->role || $user->role->name !== 'paciente') {
            abort(403, 'Esta página es solo para pacientes.');
        }

        // Cargar datos del usuario
        $this->name = $user->name;
        $this->email = $user->email;
        
        // Verificar si el usuario tiene contraseña que no sea la por defecto
        $defaultPassword = \App\Http\Middleware\EnsurePasswordChanged::DEFAULT_PASSWORD;
        $this->hasPassword = !empty($user->password) && !Hash::check($defaultPassword, $user->password);

        // Cargar datos personales si existen
        if ($user->personalData) {
            $this->hasPersonalData = true;
            $this->phone = $user->personalData->phone ?? '';
            $this->address = $user->personalData->address ?? '';
            $this->birth_date = $user->personalData->birth_date ? 
                $user->personalData->birth_date->format('Y-m-d') : '';
            $this->gender = $user->personalData->gender ?? '';
            $this->age = $user->personalData->age;
            $this->profile_photo_path = $user->personalData->profile_photo ?? '';
        }
    }

    public function saveProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:10',
            'address' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date|before:today',
            'profile_photo' => 'nullable|image|max:5120',
        ]);

        try {
            $user = Auth::user();
            // Actualizar nombre del usuario
            $user->update(['name' => $this->name]);

            // Manejar la subida de foto de perfil
            $photoPath = $this->profile_photo_path;
            if ($this->profile_photo) {
                // Eliminar foto anterior si existe
                if ($user->personalData && $user->personalData->profile_photo) {
                    Storage::disk('public')->delete($user->personalData->profile_photo);
                }
                $photoPath = $this->profile_photo->store('profile-photos', 'public');
            }

            // Actualizar datos personales si existen
            if ($this->hasPersonalData) {
                $user->personalData->update([
                    'phone' => $this->phone,
                    'address' => $this->address,
                    'birth_date' => $this->birth_date,
                    'gender' => $this->gender, // Preservar el género asignado por el nutricionista
                    'profile_photo' => $photoPath,
                ]);
                $this->profile_photo_path = $photoPath;
            }

            $this->profile_photo = null;

            // Redirigir para mostrar el toast
            return redirect()->to(route('paciente.profile'))->with('success', 'Tu perfil ha sido actualizado correctamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al actualizar el perfil: ' . $e->getMessage());
        }
    }

    public function updatePassword()
    {
        // Validar contraseña
        $rules = [];
        $messages = [];
        
        if ($this->hasPassword) {
            $rules['current_password'] = 'required|string';
            $messages['current_password.required'] = 'Debes ingresar tu contraseña actual para cambiarla.';
        }
        
        $rules['password'] = 'required|string|min:8|confirmed';
        $messages['password.required'] = 'La nueva contraseña es obligatoria.';
        $messages['password.min'] = 'La nueva contraseña debe tener al menos 8 caracteres.';
        $messages['password.confirmed'] = 'Las contraseñas no coinciden.';

        $this->validate($rules, $messages);

        try {
            $user = Auth::user();

            // Verificar contraseña actual si existe
            if ($this->hasPassword && !Hash::check($this->current_password, $user->password)) {
                $this->addError('current_password', 'La contraseña actual es incorrecta.');
                return;
            }

            // Actualizar contraseña
            $user->update([
                'password' => Hash::make($this->password)
            ]);

            // Enviar notificación de seguridad
            $user->notify(new PasswordChangedNotification());

            // Limpiar campos
            $this->reset(['current_password', 'password', 'password_confirmation']);
            $this->hasPassword = true;

            // Redirigir para mostrar el toast
            return redirect()->to(route('paciente.profile'))->with('password_success', 'Tu contraseña ha sido actualizada correctamente.');
        } catch (\Exception $e) {
            session()->flash('password_error', 'Error al actualizar la contraseña: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.settings.user-profile');
    }
}
