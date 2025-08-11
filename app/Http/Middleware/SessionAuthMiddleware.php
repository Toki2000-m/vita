<?php

namespace App\Http\Middleware;

use Closure;

class SessionAuthMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!session()->has('usuario_id')) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
