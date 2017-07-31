<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

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
        //TODO This needs to *not* use the session() on $request.
        $request->session()->forget('errors');
        //dump("RespondErrorsMiddleware 1");
        //dump(Auth::user());
        $result = $next($request);
        //dump("RespondErrorsMiddleware 2");
        //dump(Auth::user());
        $errors = $request->session()->get('errors');
        if ($errors != null) {
            $errors = $errors->default;
            if (!empty($errors)) {
                return response()->validationErrors($errors->getMessages());
            }
        }
        return $result;
    }
}
