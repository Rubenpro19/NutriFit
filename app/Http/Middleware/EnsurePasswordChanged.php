<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordChanged
{
    /**
     * Contraseña por defecto para usuarios OAuth
     */
    const DEFAULT_PASSWORD = 'DefaultOAuthPassword2024!';

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Solo aplicar a pacientes autenticados
        if ($user && $user->isPaciente()) {
            // Verificar si el usuario tiene la contraseña por defecto
            if (Hash::check(self::DEFAULT_PASSWORD, $user->password)) {
                // Permitir acceso solo a la ruta de cambiar contraseña y logout
                if (!$request->routeIs('paciente.change-default-password') && !$request->routeIs('logout')) {
                    return redirect()->route('paciente.change-default-password')
                        ->with('warning', 'Por seguridad, debes cambiar tu contraseña temporal antes de continuar.');
                }
            }
        }

        return $next($request);
    }
}
