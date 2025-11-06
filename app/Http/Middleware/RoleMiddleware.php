<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        // Si el usuario no está autenticado o no tiene rol
        if (!$user || !$user->role) {
            abort(403, 'Acceso no autorizado.');
        }

        // Verificar si coincide el rol
        if ($user->role->name !== $role) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}
