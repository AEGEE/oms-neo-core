<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\RegisterMicroserviceRequest;

use App\Models\GlobalOption;
use App\Models\Module;
use App\Models\ModulePage;

use Input;

class ModuleController extends Controller
{
    public function getModules(Module $mod, Request $req) {
        $modules = $this->getModulesFromRegistry();
        $modulesCount = count($modules);

        $limit = empty(Input::get('rows')) ? 10 : Input::get('rows');
        $page = empty(Input::get('page')) ? 1 : Input::get('page');

        if($modulesCount == 0) {
            $numPages = 0;
        } else {
            $numPages = ceil($modulesCount / $limit);
        }

        $toReturn = array(
            'rows'      =>  array(),
            'records'   =>  $modulesCount,
            'page'      =>  $page,
            'total'     =>  $numPages
        );

        foreach($modules as $module) {
            if (empty($module->modules)) {
                continue; //No modules to toggle, skip
            }
            $dbModule = Module::where('code', $module->modules->code)->first();
            //dump($dbModule);

            $active = empty($dbModule) ? 0 : $dbModule->is_active;
            $code = empty($dbModule) ? $module->modules->code : $dbModule->code;
            $actions = "";

            switch ($active) {
                case 0:
                    $toolTipTitle = "Activate";
                    $toolTip = "fa-check";
                    $actions .= "<button class='btn btn-default btn-xs clickMeModule' title='".$toolTipTitle."' ng-click='vm.activateDeactivateModule(".$code.", \"".$toolTipTitle."\")'><i class='fa ".$toolTip."'></i></button>";
                    break;
                case 1:
                    $toolTipTitle = "Deactivate";
                    $toolTip = "fa-ban";
                    $actions .= "<button class='btn btn-default btn-xs clickMeModule' title='".$toolTipTitle."' ng-click='vm.activateDeactivateModule(".$code.", \"".$toolTipTitle."\")'><i class='fa ".$toolTip."'></i></button>";
                    break;
            }

            $toReturn['rows'][] = array(
                'code'    =>  $code,
                'cell'  =>  array(
                    $actions,
                    $module->name,
                    empty($module->url) ? "-" : $module->url,
                    $active == 1 ? "Active" : "Inactive"
                )
            );
        }

        return response(json_encode($toReturn), 200);
    }


    public function getModulePages(ModulePage $page, Request $req) {
        $pages = $this->getModulePagesFromRegistry();
        $pagesCount = count($pages);

        $limit = empty(Input::get('rows')) ? 10 : Input::get('rows');
        $page = empty(Input::get('page')) ? 1 : Input::get('page');

        if($pagesCount == 0) {
            $numPages = 0;
        } else {
            $numPages = ceil($pagesCount / $limit);
        }

        $toReturn = array(
            'rows'      =>  array(),
            'records'   =>  $pagesCount,
            'page'      =>  $page,
            'total'     =>  $numPages
        );

        foreach($pages as $page) {
            $dbPage = ModulePage::where('code', $page->code)->first();

            $active = empty($dbPage) ? 0 : $dbPage->is_active;
            $code = empty($dbPage) ? $page->code : $dbPage->code;
            $actions = "";

            switch ($active) {
                case 0:
                    $toolTipTitle = "Activate";
                    $toolTip = "fa-check";
                    $actions .= "<button class='btn btn-default btn-xs clickMeModulePage' title='".$toolTipTitle."' ng-click='vm.activateDeactivatePage(".$code.", \"".$toolTipTitle."\")'><i class='fa ".$toolTip."'></i></button>";
                    break;
                case 1:
                    $toolTipTitle = "Deactivate";
                    $toolTip = "fa-ban";
                    $actions .= "<button class='btn btn-default btn-xs clickMeModulePage' title='".$toolTipTitle."' ng-click='vm.activateDeactivatePage(".$code.", \"".$toolTipTitle."\")'><i class='fa ".$toolTip."'></i></button>";
                    break;
                }

        	$toReturn['rows'][] = array(
        		'code'	=>	$code,
        		'cell'	=> 	array(
        			$actions,
        			$page->name,
        			$code,
                    $active == 1 ? "Active" : "Inactive",
        			$page->name
        		)
        	);
        }

        return response(json_encode($toReturn), 200);
    }

    private function getModulesFromRegistry() {
        $client = new \GuzzleHttp\Client();
        // $res = $client->get('http://localhost:7000/services');
        $res = $client->get('http://omsserviceregistry:7000/services');
        $services = json_decode($res->getBody());
        // echo $res->getStatusCode(); // 200

        $modules = $services->data;
        return $modules;
    }

    private function getModuleFromRegistry($code) {
        $modules = $this->getModulesFromRegistry();
        foreach ($modules as $module) {
            if ($module->code == $code) {
                return $module;
            }
        }
        return null;
    }

    private function getModulePagesFromRegistry() {
        $modules = $this->getModulesFromRegistry();

        $pages = array();
        foreach($modules as $module) {
            if (!empty($module->modules)) {
                $pages = array_merge($pages, $module->modules->pages);
            }
        }
        return $pages;
    }

    private function getModulePageFromRegistry($code) {
        $pages = $this->getModulePagesFromRegistry();
        foreach ($pages as $page) {
            if ($page->code == $code) {
                return $page;
            }
        }
        return null;
    }

    public function activateDeactivatePage($code) {
        $dbPage = ModulePage::where('code', $code)->first();

        if (empty($dbPage)) {
            // Not in db yet, create new record.
            $page = $this->getModulePageFromRegistry($code);
            dump($page);
            $newPage = new ModulePage();
            $newPage->is_active = 1;
            $newPage->name = $page->name;
            $newPage->code = $page->code;
            $newPage->module_link = $page->module_link;
            $newPage->icon = $page->icon;
            $newPage->save();
        } else {
            // Already in db, toggle active state.
            if(!empty($dbPage->is_active)) {
                $dbPage->is_active = null;
            } else {
                $dbPage->is_active = 1;
            }
            $dbPage->save();
        }

        $toReturn['success'] = 1;
        return response(json_encode($toReturn), 200);
    }

    public function activateDeactivateModule($code) {
        $dbModule = Module::where('code', $code)->first();

        if (empty($dbModule)) {
            // Not in db yet, create new record.
            $module = $this->getModuleFromRegistry($code);

            $newMod = new Module();
            $newMod->is_active = 1;
            $newMod->name = $module->name;
            $newMod->code = $code;
            $newMod->base_url = $module->url;
            $newMod->save();
        } else {
            // Already in db, toggle active state.
            if(!empty($dbModule->is_active)) {
                $dbModule->is_active = null;
            } else {
                $dbModule->is_active = 1;
            }
            $mod->save();
        }

        $toReturn['success'] = 1;
        return response(json_encode($toReturn), 200);
    }

    public function getSharedSecret(GlobalOption $opt) {
        $opt = $opt->where('code', 'shared_secret')->first();
        if($opt == null) {
            $opt = new GlobalOption();
            $opt->name = "Shared secret";
            $opt->code = "shared_secret";
            $opt->not_editable = 1;
            $opt->value = $opt->generateNewSecretToken();
            $opt->save();
        }

        $toReturn['success'] = 1;
        $toReturn['key'] = $opt->value;

        return response(json_encode($toReturn), 200);
    }

    public function generateNewSharedSecret(GlobalOption $opt) {
        $opt = $opt->where('code', 'shared_secret')->firstOrFail();
        $opt->value = $opt->generateNewSecretToken();
        $opt->save();

        $toReturn['success'] = 1;
        $toReturn['key'] = $opt->value;

        return response(json_encode($toReturn), 200);
    }

    /* ALL MODULE REGISTRATION GOES HERE! */
    public function registerMicroservice(Module $mod, GlobalOption $opt, RegisterMicroserviceRequest $req) {
        return response()->notImplemented();
    }
}
