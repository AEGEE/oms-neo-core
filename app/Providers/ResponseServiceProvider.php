<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class ResponseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('success', function ($data, $meta = null, $message = null) {
            $response = array(
                'success'   => true,
                'meta'      => $meta,
                'data'      => $data,
                'message'   => $message
            );
            return response()->json($response);
        });
        Response::macro('failure', function ($message = 'Request failed', $code = 400) {
            $response = array(
                'success'   => false,
                'message'   => $message
            );
            return response()->json($response, $code);
        });

        Response::macro('forbidden', function () {
            return response()->failure('Forbidden', 403);
        });
        Response::macro('unauthorized', function () {
            //TODO add WWW-Authenticate header.
            return response()->json('Unauthorized', 401);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
