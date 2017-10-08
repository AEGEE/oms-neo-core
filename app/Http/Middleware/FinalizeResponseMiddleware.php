<?php

namespace App\Http\Middleware;

use Closure;

class FinalizeResponseMiddleware
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
        $result = $next($request);
        //TODO: Find a better APIResponse implementation / solution to this problem
        $result->finalize();
        return $result;
    }
}
