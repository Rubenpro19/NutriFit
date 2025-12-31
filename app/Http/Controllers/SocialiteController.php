<?php

namespace App\Http\Controllers;

use App\Models\User;
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

            // Buscar o crear usuario
            $user = User::firstOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'email_verified_at' => now(),
                    'password' => bcrypt(\App\Http\Middleware\EnsurePasswordChanged::DEFAULT_PASSWORD),
                    'role_id' => 3,
                    'user_state_id' => 1,
                ]
            );

            Auth::login($user, true);

            return redirect()->route('paciente.dashboard');

        } catch (\Exception $e) {
            return redirect('/login')->withErrors('Error al autenticar con Google.');
        }
    }
}
