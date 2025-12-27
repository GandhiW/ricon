<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        // global middleware
    ];

    protected $middlewareGroups = [
        'web' => [
            // middleware untuk web
        ],
        'api' => [
            // middleware untuk API
        ],
    ];

    protected $routeMiddleware = [
        // middleware khusus route
        'jwt.verify' => \App\Http\Middleware\VerifyJWT::class,
    ];
}
