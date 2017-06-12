<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\OnlineBusinessEnvironment;
use App\Proxies\MicrosoftGraphProxy;

class OnlineBusinessEnvironmentProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

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
        $this->app->singleton(OnlineBusinessEnvironment::class, function ($app) {
            return new MicrosoftGraphProxy($app);
        });
    }
}
