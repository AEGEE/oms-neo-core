<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\AuthToken;
use App\Models\Country;
use App\Models\GlobalOption;
use App\Models\MenuItem;
use App\Models\ModulePage;
use App\Models\UserRole;
use App\Http\Middleware\LoginMethodMiddleware;

use Session;
use Auth;

class GenericController extends Controller
{
    public function defaultRoute(GlobalOption $opt, AuthToken $auth, MenuItem $menuItem) {
    	$userData = Auth::user();
        $addToView = array();

        // Options..
        $optionsArr = array();
        $allOptions = $opt->all();
        foreach ($allOptions as $option) {
            $optionsArr[$option->code] = $option->value;
        }
        session_start();
        $_SESSION['globals'] = $optionsArr;
        $_SESSION['app_version'] = $this->getAppVersion();
        session_write_close();

        $addToView['appName'] = $optionsArr['app_name'];
        if($auth->isUserLogged($userData['authToken'])) {
            $systemRolesAccess = array();

            $addToView['userData'] = Auth::user();
            $addToView['countries'] = "";
            $addToView['modulesSrc'] = "";
            $addToView['baseUrlRepo'] = "";
            $addToView['modulesNames'] = "";
            $addToView['moduleAccess'] = "";
            $addToView['authToken'] = $userData['authToken'];

            // Adding modules to which he has access..
            if($userData['is_superadmin'] == 1) {
                // Has access to all modules, regardless of roles assigned..
                $modules = ModulePage::with('module')->whereNotNull('module_pages.is_active')
                                        ->orderBy('module_pages.module_id', 'ASC NULLS FIRST')
                                        ->orderBy('module_pages.name', 'ASC')->get();
            } else {
                if($userData['is_suspended']) {
                    $addToView['suspention'] = $userData['suspended_reason'];
                    return view('loggedIn', $addToView);
                }
                // Getting module pages ids to which it has access to..
                $userRolesObj = new UserRole();
                $modulePageIds = $userRolesObj->getModulePagesIdForUser($userData['id']);
                $modules = ModulePage::with('module')->whereNotNull('module_pages.is_active')
                                        ->whereIn('module_pages.id', array_keys($modulePageIds))
                                        ->orderBy('module_pages.module_id', 'ASC NULLS FIRST')
                                        ->orderBy('module_pages.name', 'ASC')->get();

                $systemRoles = UserRole::distinct('code')
                                        ->join('roles', 'roles.id', '=', 'user_roles.role_id')
                                        ->where('user_id', $userData['id'])
                                        ->whereNull('is_disabled')
                                        ->get();

                foreach($systemRoles as $roleX) {
                    $systemRolesAccess[] = $roleX->code;
                }

            }

            $lastModuleId = 0;
            $menuMarkUp = "";
            $moduleAccess = array();
            foreach($modules as $module) {
                $moduleBase = empty($module->module_id) ? "" : $module->module->base_url."/";

                if(!empty($module->module_id) && empty($module->module->is_active)) {
                    continue;
                }

                $addToView['modulesSrc'] .= "<script type='text/javascript' src='".$moduleBase.$module->module_link."'></script>";
                $addToView['modulesNames'] .= ", 'app.".$module->code."'";

                if($lastModuleId != $module->module_id) {
                    if(strlen($addToView['baseUrlRepo']) > 0) {
                        $addToView['baseUrlRepo'] .= ",";
                    }

                    $addToView['baseUrlRepo'] .= "'".$module->module->code."': '".$moduleBase."'";
                    $lastModuleId = $module->module_id;
                    $menuMarkUp .= '<li class="nav-header">'.$module->module->name.'</li>';
                }

                $menuMarkUp .= '<li ui-sref-active="active"><a ui-sref="app.'.$module->code.'"><i class="'.$module->icon.'"></i> <span>'.$module->name.'</span></a></li>';

                if(strlen($addToView['moduleAccess']) > 0) {
                    $addToView['moduleAccess'] .= ", ";
                }

                if($userData['is_superadmin'] == 1) {
                    $addToView['moduleAccess'] .= $module->code.": 1";
                    $moduleAccess[$module->code] = 1;
                } else {
                    $addToView['moduleAccess'] .= $module->code.": ".$modulePageIds[$module->id];
                    $moduleAccess[$module->code] = $modulePageIds[$module->id]; // todo get highest access level..
                }
            }

            $menuMarkUpNew = $menuItem->getMenuMarkup($moduleAccess);

            // Mirroring links accessible to session so they can be accessed in partials..
            session_start();
            $_SESSION['moduleMarkup'] = $menuMarkUp;

            if(strlen($menuMarkUpNew) > 0) {
                $_SESSION['moduleMarkup'] = $menuMarkUpNew;
            }

            $_SESSION['moduleAccess'] = $moduleAccess;
            $_SESSION['systemRoles'] = $systemRolesAccess;
            session_write_close();

            Session::put('moduleAccess', $moduleAccess);

            // Countries..
            $countries = Country::all();
            $countriesMarkup = "";
            foreach($countries as $country) {
                if(strlen($countriesMarkup) > 0) {
                    $countriesMarkup .= ",";
                }
                $countriesMarkup .= $country->id.':"'.$country->name.'"'."\n";
            }
            $addToView['countries'] = $countriesMarkup;

    		return view('loggedIn', $addToView);
    	}

        $addToView['oAuthDefined'] = LoginMethodMiddleware::isOauthDefined();
		return view('notLogged', $addToView);
    }

    public function logout(AuthToken $auth) {
        $userData = Auth::user();
        Auth::logout();
        // Invalidating api key if exists..
        if(!empty($userData)) {
            $auth = $auth->where('token_generated', $userData['authToken'])->firstOrFail();
            $auth->expiration = date('Y-m-d H:i:s');
            $auth->save();
        }
        Session::flush();
        session_start();
        session_destroy();
        return redirect('/');
    }

    public function noSessionTimeout() {
        $now = date('now');

        Session::put('lastActive', $now);

        session_start();
        $_SESSION['lastActive'] = $now;
        session_write_close();

        return 1;
    }

    public function getNotifications(Request $req) {
        //TODO implement notifications.
    }

    public function markNotificationsAsRead(Request $req) {
        //TODO implement notifications.
    }
}
