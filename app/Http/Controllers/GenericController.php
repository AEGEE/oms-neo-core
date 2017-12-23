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
use App\Models\User;
use App\Http\Middleware\LoginMethodMiddleware;
use Session;
use Auth;

class GenericController extends Controller
{
    public function defaultRoute(GlobalOption $opt, MenuItem $menuItem) {
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
        $systemRolesAccess = array();

        $addToView['countries'] = "";
        $addToView['modulesSrc'] = "";
        $addToView['baseUrlRepo'] = "";
        $addToView['modulesNames'] = "";
        $addToView['moduleAccess'] = "";


        // Render all possible modules, TODO decide in the frontend which ones the user can see
        $modules = ModulePage::with('module')->whereNotNull('module_pages.is_active')
                                ->orderBy('module_pages.module_code', 'ASC NULLS FIRST')
                                ->orderBy('module_pages.name', 'ASC')->get();

        $lastModuleId = "";
        $menuMarkUp = "";
        $moduleAccess = array();
        foreach($modules as $module) {
            $moduleBase = empty($module->module_code) ? "" : $module->module->base_url."/";

            if(!empty($module->module_code) && empty($module->module->is_active)) {
                continue;
            }

            $addToView['modulesSrc'] .= "<script type='text/javascript' src='".$moduleBase.$module->module_link."'></script>";
            $addToView['modulesNames'] .= ", 'app.".$module->code."'";

            if($lastModuleId != $module->module_code) {
                if(strlen($addToView['baseUrlRepo']) > 0) {
                    $addToView['baseUrlRepo'] .= ",";
                }

                $addToView['baseUrlRepo'] .= "'".$module->module->code."': '".$moduleBase."'";
                $lastModuleId = $module->module_code;
                $menuMarkUp .= '<li class="nav-header">'.$module->module->name.'</li>';
            }

            $menuMarkUp .= '<li ui-sref-active="active"><a ui-sref="app.'.$module->code.'"><i class="'.$module->icon.'"></i> <span>'.$module->name.'</span></a></li>';

            if(strlen($addToView['moduleAccess']) > 0) {
                $addToView['moduleAccess'] .= ", ";
            }


            $addToView['moduleAccess'] .= $module->code.": 1";
            $moduleAccess[$module->code] = 1;

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


        // TODO is this still used?
        $addToView['oAuthDefined'] = LoginMethodMiddleware::isOauthDefined();

        // Always return the loggedIn view, the frontend will check if it finds a valid access token in the localStorage of the client and if not, log in
		return view('template', $addToView);
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
