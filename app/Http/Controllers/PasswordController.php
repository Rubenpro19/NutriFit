<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Middleware\EnsurePasswordChanged;
use App\Notifications\PasswordChangedNotification;

class PasswordController extends Controller
{
    public function showChangePassword()
    {
        $user = Auth::user();
        
        // Si no tiene la contraseña por defecto, redirigir al dashboard
        $defaultPassword = EnsurePasswordChanged::DEFAULT_PASSWORD;
        if (!Hash::check($defaultPassword, $user->password)) {
            return redirect()->route('paciente.dashboard');
        }

        return view('paciente.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        try {
            $user = Auth::user();

            // Cambiar la contraseña
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            // Enviar notificación de seguridad
            $user->notify(new PasswordChangedNotification());

            return redirect()->route('paciente.dashboard')
                ->with('success', '¡Contraseña actualizada exitosamente! Ahora puedes acceder con tu nueva contraseña.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al cambiar la contraseña: ' . $e->getMessage());
        }
    }
}
