<?php

namespace App\Http\Middleware;

use Closure;
use Input;

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
        $size = Input::get('page_size', 10);
        $result = $next($request);
        $response = $result->getOriginalContent();
        if (isset($response['data']) && is_a($response['data'], 'Illuminate\Database\Eloquent\Builder')) {
            $response['data'] = $response['data']->paginate($size);
            $response = json_encode($response);
            $result->setContent($response);
        }
        return $result;
    }
}
