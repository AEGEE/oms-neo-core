<?php

namespace App\Http\Middleware;

use Illuminate\Database\Eloquent\ModelNotFoundException;

use Closure;

use App\Models\Auth;

class ApiCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Checking user data and api key..
        $xAuthToken = isset($_SERVER['HTTP_X_AUTH_TOKEN']) ? $_SERVER['HTTP_X_AUTH_TOKEN'] : '';

        if(empty($xAuthToken)) {
            return response()->unauthorized();
        }

        $now = date('Y-m-d H:i:s');

        try {
            $auth = Auth::where('token_generated', $xAuthToken)
                        ->where(function($query) use($now) {
                            $query->where('expiration', '>', $now)
                                    ->orWhereNull('expiration');
                        })
                        ->firstOrFail();
        } catch(ModelNotFoundException $ex) {
            return response()->forbidden();
        }

        $request->attributes->add(['userData' => $auth->user]);

        return $next($request);
    }
}
