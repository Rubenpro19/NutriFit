<?php

namespace App\Actions;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class RedirectAfterLogin implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return redirect()->route('dashboard');
        }

        if ($user->isNutricionista()) {
            return redirect()->route('nutricionista.dashboard');
        }

        if ($user->isPaciente()) {
            return redirect()->route('paciente.dashboard');
        }

        return redirect()->route('home');
    }
}
