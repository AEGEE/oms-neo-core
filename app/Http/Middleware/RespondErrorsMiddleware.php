<?php

namespace App\Http\Middleware;

use Closure;

class RespondErrorsMiddleware {
    /**
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
        $errors = $request->session()->get('errors')->default;
        //dd($errors);
        if (!empty($errors)) {
            return response()->validationErrors($errors);
        }

        return $result;
    }
}
