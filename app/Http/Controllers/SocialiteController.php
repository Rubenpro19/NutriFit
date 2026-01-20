<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    // Redirige a Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Callback de Google
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Buscar usuario existente
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // Usuario existe, iniciar sesión directamente
                Auth::login($user, true);
                return redirect()->route('paciente.dashboard');
            }

            // Usuario no existe, guardar datos en sesión y redirigir a pantalla de consentimiento
            session([
                'google_user' => [
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                ]
            ]);

            return redirect()->route('google.consent');

        } catch (\Exception $e) {
            return redirect('/login')->withErrors('Error al autenticar con Google.');
        }
    }

    // Mostrar pantalla de consentimiento para usuarios de Google
    public function showConsentForm()
    {
        $googleUser = session('google_user');

        if (!$googleUser) {
            return redirect()->route('login')->withErrors('Sesión expirada. Por favor, intenta nuevamente.');
        }

        return view('auth.google-consent', compact('googleUser'));
    }

    // Procesar consentimiento y crear usuario
    public function processConsent(Request $request)
    {
        $googleUser = session('google_user');

        if (!$googleUser) {
            return redirect()->route('login')->withErrors('Sesión expirada. Por favor, intenta nuevamente.');
        }

        $request->validate([
            'data_consent' => ['required', 'accepted'],
        ], [
            'data_consent.required' => 'Debes aceptar el tratamiento de datos personales para continuar.',
            'data_consent.accepted' => 'Debes aceptar el tratamiento de datos personales para continuar.',
        ]);

        // Crear usuario con consentimiento
        $user = User::create([
            'name' => $googleUser['name'],
            'email' => $googleUser['email'],
            'email_verified_at' => now(),
            'password' => bcrypt(\App\Http\Middleware\EnsurePasswordChanged::DEFAULT_PASSWORD),
            'role_id' => 3,
            'user_state_id' => 1,
            'data_consent' => true,
            'data_consent_at' => now(),
        ]);

        // Limpiar sesión
        session()->forget('google_user');

        Auth::login($user, true);

        return redirect()->route('paciente.dashboard');
    }
}
