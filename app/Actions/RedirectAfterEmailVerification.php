<?php

namespace App\Actions;

use Laravel\Fortify\Contracts\VerifyEmailResponse as VerifyEmailResponseContract;

class RedirectAfterEmailVerification implements VerifyEmailResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        $route = match (true) {
            $user->isAdmin() => 'admin.dashboard',
            $user->isNutricionista() => 'nutricionista.dashboard',
            $user->isPaciente() => 'paciente.dashboard',
            default => 'home',
        };

        return redirect()->intended(route($route).'?verified=1');
    }
}
