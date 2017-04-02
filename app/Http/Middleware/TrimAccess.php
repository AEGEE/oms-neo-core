<?php

namespace App\Http\Middleware;

use Closure;

class TrimAccess
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

        $content = $result->getOriginalContent();
        $json = json_encode($content);
        $json = preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $json);
        return response($json)->header('Content-Type', "application/json");
    }
}
