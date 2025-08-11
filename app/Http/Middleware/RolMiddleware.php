<?php

namespace App\Http\Middleware;

use Closure;

class RolMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        $rol = session('rol_user');

        if (!$rol || !in_array($rol, $roles)) {
            abort(403, 'Acceso no autorizado');
        }

        return $next($request);
    }
}
