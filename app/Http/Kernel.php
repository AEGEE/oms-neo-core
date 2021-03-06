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
        'Barryvdh\Cors\HandleCors',
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        ],

        'api' => [
            'returnErrors',
            'paginate',
            'checkKey',
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
        'auth'                  => \App\Http\Middleware\Authenticate::class,
        'auth.basic'            => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'can'                   => \Illuminate\Foundation\Http\Middleware\Authorize::class,
        'guest'                 => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle'              => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'checkKey'              => \App\Http\Middleware\ApiCheckMiddleware::class,
        'checkAccess'           => \App\Http\Middleware\CheckModuleAccess::class,
        'microServiceAuth'      => \App\Http\Middleware\MicroServiceAuth::class,
        'checkSpecialRoles'     => \App\Http\Middleware\CheckSpecialRoles::class,
        'seoURL'                => \App\Http\Middleware\SeoURLMiddleware::class,
        'login'                 => \App\Http\Middleware\LoginMethodMiddleware::class,
        'returnErrors'          => \App\Http\Middleware\RespondErrorsMiddleware::class,
        'paginate'          => \App\Http\Middleware\PaginatorMiddleware::class,
    ];
}
