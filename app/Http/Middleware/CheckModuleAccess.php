<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Models\ModulePage;
use App\Models\RoleModulePage;
use App\Models\User;

use DB;

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
        $user = $request->get('userData');
        
        if(!empty($user->forceGetAttribute("is_suspended"))) {
            return response('Forbidden', 403);
        }

        try {
            $modulePage = ModulePage::with('module')->whereNotNull('is_active')->where('code', $moduleCode)->firstOrFail();
        } catch(ModelNotFoundException $ex) {
            return response('Forbidden', 403);
        }

        if(!empty($modulePage->module_id) && empty($modulePage->module->is_active)) {
            return response('Forbidden', 403);
        }

        $max_permission = 1;
        if($user->is_superadmin == 1) {
            // User is superadmin.. can access any module..
            $request->attributes->add(['max_permission' => $max_permission]);
            return $next($request);
        }

        // Else we need to check if user has a role which allows him to access it..
        $canAccess = RoleModulePage::select(DB::raw('max(permission_level) as max_permission, count(role_module_pages.id) as role_exists'))
                                    ->join('roles', 'roles.id', '=', 'role_module_pages.role_id')
                                    ->join('user_roles', 'roles.id', '=', 'user_roles.role_id')
                                    ->where('user_roles.user_id', $userData->id)
                                    ->whereNull('roles.is_disabled')
                                    ->where('role_module_pages.module_page_id', $modulePage->id);

        if($request->isMethod('post')) {
            $canAccess = $canAccess->where('permission_level', 1);
        }


        $canAccess = $canAccess->first();
        if($canAccess->role_exists == 0) {
            return response('Forbidden', 403);
        }
        $max_permission = $canAccess->max_permission;
        $request->attributes->add(['max_permission' => $max_permission]);

        return $next($request);
    }
}
