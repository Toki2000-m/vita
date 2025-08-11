<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        // middleware global
    ];

    protected $middlewareGroups = [
        'web' => [
            // middleware para web
        ],
        'api' => [
            // middleware para API
        ],
    ];

    protected $routeMiddleware = [
    // otros middlewares...
    'rol' => \App\Http\Middleware\RolMiddleware::class,
     'session.auth' => \App\Http\Middleware\SessionAuthMiddleware::class,
];

}
