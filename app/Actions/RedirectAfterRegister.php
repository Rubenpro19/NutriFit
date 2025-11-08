<?php

namespace App\Actions;

use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RedirectAfterRegister implements RegisterResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
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
