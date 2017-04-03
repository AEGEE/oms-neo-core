<?php

namespace App\Http\Middleware;

use Closure;

use App\Repositories\RolesRepository as Repo;

class SetRolesMiddleware
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
        //Set the global roles for the user.
        $request->get('userData')->syncRoles();

        return $next($request);
    }
}
