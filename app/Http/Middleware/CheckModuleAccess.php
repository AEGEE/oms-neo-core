<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Models\ModulePage;
use App\Models\RoleModulePage;

class CheckModuleAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $moduleCode)
    {
        $userData = $request->get('userData');

        try {
            $modulePage = ModulePage::with('module')->whereNotNull('is_active')->where('code', $moduleCode)->firstOrFail();
        } catch(ModelNotFoundException $ex) {
            return response('Forbidden', 403);
        }

        if(!empty($modulePage->module_id) && empty($modulePage->module->is_active)) {
            return response('Forbidden', 403);
        }

        if($userData->is_superadmin == 1) {
            // User is superadmin.. can access any module..
            return $next($request);
        }

        // Else we need to check if user has a role which allows him to access it..
        $canAccess = RoleModulePage::join('roles', 'roles.id', '=', 'role_module_pages.role_id')
                                    ->join('user_roles', 'roles.id', '=', 'user_roles.role_id')
                                    ->where('user_roles.user_id', $userData->id)
                                    ->whereNull('roles.is_disabled')
                                    ->count();
        if($canAccess == 0) {
            return response('Forbidden', 403);
        }

        return $next($request);
    }
}
