<?php

namespace App\Http\Middleware;

use Closure;

class PaginatorMiddleware {    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $result = $next($request);
        $response = $result->getOriginalContent();
        if (is_a($response['data'], 'Illuminate\Database\Eloquent\Builder')) {
            $response['data'] = $response['data']->paginate(2);
            $response = json_encode($response);
            $result->setContent($response);
        }
        return $result;
    }
}
