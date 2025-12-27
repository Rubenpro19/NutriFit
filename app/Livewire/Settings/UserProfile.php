<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\PersonalData;
use Illuminate\Support\Facades\Auth;

class UserProfile extends Component
{
    public $name;
    public $email;
    public $phone = '';
    public $address = '';
    public $birth_date = '';
    public $gender = '';
    public $age = null;

    public $hasPersonalData = false;

    protected function rules()
    {
        return [
            'phone' => 'nullable|string|max:10',
            'address' => 'nullable|string|max:255',
        ];
    }

    protected $messages = [
        'phone.max' => 'El teléfono no puede tener más de 10 caracteres.',
        'address.max' => 'La dirección no puede tener más de 255 caracteres.',
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

        // Cargar datos personales si existen
        if ($user->personalData) {
            $this->hasPersonalData = true;
            $this->phone = $user->personalData->phone ?? '';
            $this->address = $user->personalData->address ?? '';
            $this->birth_date = $user->personalData->birth_date ? 
                $user->personalData->birth_date->format('Y-m-d') : '';
            $this->gender = $user->personalData->gender ?? '';
            $this->age = $user->personalData->age;
        }
    }

    public function save()
    {
        // Verificar que el usuario tiene datos personales
        if (!$this->hasPersonalData) {
            session()->flash('error', 'No puedes actualizar tus datos personales. El nutricionista debe completarlos primero.');
            return;
        }

        $this->validate();

        try {
            $user = Auth::user();
            
            // Actualizar solo los campos permitidos (NO el género ni fecha de nacimiento)
            $user->personalData->update([
                'phone' => $this->phone,
                'address' => $this->address,
            ]);

            session()->flash('success', 'Tus datos personales han sido actualizados correctamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al actualizar los datos: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.settings.user-profile');
    }
}
