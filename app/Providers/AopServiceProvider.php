<?php

namespace App\Providers;

use App\Aspect\PermissionAspect;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;

use Log;

class AopServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(PermissionAspect::class, function (Application $app) {
            return new PermissionAspect();
        });

        $this->app->tag([PermissionAspect::class], 'goaop.aspect');
    }
}
