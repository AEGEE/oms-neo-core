<?php

namespace App\Http\Middleware;

use Closure;

use App\Repositories\RolesRepository;

class SetRolesMiddleware
{
    public $repo;

    public function __construct(RolesRepository $rolerepo) {
      $this->repo = $rolerepo;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //Get source user
        $user = $request->get('userData');
        $request->attributes->add(['roles_source' => $user]);

        //Get user global roles
        $repo = $this->repo;
        $globalRoles = $repo->getGlobalRoles($user);
        $user->setRoles($globalRoles);

        return $next($request);
    }
}
