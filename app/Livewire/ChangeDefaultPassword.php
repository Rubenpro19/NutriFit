<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangeDefaultPassword extends Component
{
    public $password = '';
    public $password_confirmation = '';

    protected $rules = [
        'password' => 'required|string|min:8|confirmed',
    ];

    protected $messages = [
        'password.required' => 'La contraseña es obligatoria.',
        'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        'password.confirmed' => 'Las contraseñas no coinciden.',
    ];

    public function mount()
    {
        $user = Auth::user();
        
        // Si no tiene la contraseña por defecto, redirigir al dashboard
        $defaultPassword = \App\Http\Middleware\EnsurePasswordChanged::DEFAULT_PASSWORD;
        if (!Hash::check($defaultPassword, $user->password)) {
            return redirect()->route('paciente.dashboard');
        }
    }

    public function changePassword()
    {
        $this->validate();

        try {
            $user = Auth::user();

            // Cambiar la contraseña
            $user->update([
                'password' => Hash::make($this->password)
            ]);

            session()->flash('success', '¡Contraseña actualizada exitosamente! Ahora puedes acceder con tu nueva contraseña.');
            
            return redirect()->route('paciente.dashboard');

        } catch (\Exception $e) {
            session()->flash('error', 'Error al cambiar la contraseña: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.change-default-password');
    }
}
