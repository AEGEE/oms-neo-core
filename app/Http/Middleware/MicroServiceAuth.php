<?php

namespace App\Http\Middleware;

use Closure;

use App\Models\Module;

class MicroServiceAuth
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
        $xAuthToken = isset($_SERVER['HTTP_X_API_KEY']) ? $_SERVER['HTTP_X_API_KEY'] : '';

        if(empty($xAuthToken)) {
            return response('Forbidden', 403);
        }

        $keyExists = Module::where('handshake_token', $xAuthToken)->whereNotNull('is_active')->count();
        if($keyExists == 0) {
            return response('Forbidden', 403);
        }

        return $next($request);
    }
}
