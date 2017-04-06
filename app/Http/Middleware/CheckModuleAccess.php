<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Models\ModulePage;
use App\Models\RoleModulePage;
use App\Models\Member;

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
    $user = $request->get('user');

    if($user->isSuperAdmin()) {
      error_log("Super admin detected.");
      $request->attributes->add(['max_permission' => 1]);
      return $next($request);
    }

    if(!$user->checkStillValid()) {
      error_log("User no longer valid.");
      return response('Forbidden', 403);
    }

    try {
      $modulePage = ModulePage::with('module')->whereNotNull('is_active')->where('code', $moduleCode)->firstOrFail();
    } catch(ModelNotFoundException $ex) {
      error_log("Module code not found.");
      return response('Forbidden', 403);
    }

    if(!empty($modulePage->module_id) && empty($modulePage->module->is_active)) {
      error_log("Module is inactive.");
      return response('Forbidden', 403);
    }



    $permission = -1;
    if ($request->isMethod('post')) {
      //TODO things like DELETE request and such should also be checked.
      $permission = $modulePage->canWrite($user->getRoles()) ? 1 : -1;
    } else {
      $permission = $modulePage->canRead($user->getRoles()) ? 0 : -1;
    }

    if ($permission < 0) {
      error_log("User does not have access to this module.");
      return response('Forbidden', 403);
    } else {
      $request->attributes->add(['max_permission' => $permission]);
      return $next($request);
    }
  }
}
