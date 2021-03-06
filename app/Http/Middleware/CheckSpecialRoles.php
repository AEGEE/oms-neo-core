<?php

namespace App\Http\Middleware;

use Closure;

use App\Models\ModulePage;
use App\Models\Role;

class CheckSpecialRoles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $moduleCode, $roleCode)
    {
        $userData = $request->get('userData');
        if(!empty($userData->is_suspended)) {
            return response()->forbidden();
        }

        if($moduleCode != 'null') {
            try {
                $modulePage = ModulePage::with('module')->whereNotNull('is_active')->where('code', $moduleCode)->firstOrFail();
            } catch(ModelNotFoundException $ex) {
                return response()->forbidden();
            }

            if(!empty($modulePage->module_id) && empty($modulePage->module->is_active)) {
                return response()->forbidden();
            }
        }

        if($userData->is_superadmin == 1) {
            // User is superadmin.. can access any module..
            return $next($request);
        }

        // Else we need to check if user has a role which allows him to access it..
        $canAccess = Role::join('user_roles', 'roles.id', '=', 'user_roles.role_id')
                            ->where('roles.code', $roleCode)
                            ->where('user_roles.user_id', $userData->id)
                            ->whereNull('roles.is_disabled')
                            ->count();

        if($canAccess == 0) {
            return response()->forbidden();
        }

        return $next($request);
    }
}
