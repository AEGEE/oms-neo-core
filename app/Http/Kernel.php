<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        'Barryvdh\Cors\HandleCors', //TODO: Do we need this?
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [

        'web' => [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        ],
        //TODO Currently web is automatically applied to *every* route in routes.php. Do we want this?
        //Currently it seems to be only used for keeping track of login attempts.

        'api' => [
            'json',
            'returnErrors',
            'auth',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth'                  => \Illuminate\Auth\Middleware\Authenticate::class,
        'can'                   => \Illuminate\Foundation\Http\Middleware\Authorize::class,
        'guest'                 => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle'              => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'seoURL'                => \App\Http\Middleware\SeoURLMiddleware::class,
        'login'                 => \App\Http\Middleware\LoginMethodMiddleware::class,
        'returnErrors'          => \App\Http\Middleware\RespondErrorsMiddleware::class,
        'json'                  => \App\Http\Middleware\AlwaysJSONMiddleware::class,
    ];
}
