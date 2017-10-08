<?php

namespace App\Providers;

use App\Http\Response\APIResponse;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\Collection;

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
            return new APIResponse(true, $meta, $data, $message);
        });
        Response::macro('failure', function ($message = 'Request failed', $code = 400, $errors = null) {
            $response = array(
                'success'   => false,
                'errors'    => $errors,
                'message'   => $message
            );
            return response()->json($response, $code);
        });

        Response::macro('forbidden', function () {
            return response()->failure('Forbidden', 403);
        });
        Response::macro('unauthorized', function () {
            //TODO add WWW-Authenticate header.
            return response()->failure('Unauthorized', 401);
        });
        Response::macro('credentialsFailure', function () {
            //TODO add WWW-Authenticate header.
            return response()->failure('Invalid login credentials.', 400);
        });
        Response::macro('validationErrors', function ($errors) {
            return response()->failure('Validation error.', 422, $errors);
        });
        Response::macro('notImplemented', function () {
            return response()->failure('Not Implemented', 501);
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
