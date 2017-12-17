<?php

namespace App\Http\Middleware;

use Illuminate\Database\Eloquent\ModelNotFoundException;

use Closure;
use Auth;

use App\Models\AuthToken;

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
        $xAuthToken = $request->headers->get('x-auth-token');

        if(empty($xAuthToken)) {
            return response()->unauthorized();
        }

        $now = date('Y-m-d H:i:s');

        try {
            $auth = AuthToken::where('token_generated', $xAuthToken)
                        ->where(function($query) use($now) {
                            $query->where('expiration', '>', $now)
                                    ->orWhereNull('expiration');
                        })
                        ->firstOrFail();
        } catch(ModelNotFoundException $ex) {
            return response()->unauthorized();
        }

        Auth::logout(); //Just to be safe, should not be necessary.
        Auth::onceUsingId($auth->user->id);

        return $next($request);
    }
}
