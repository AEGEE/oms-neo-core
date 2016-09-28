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
        $max_permission = $req->get('max_permission');
        $search = array(
            'name'          =>  Input::get('name'),
            'active'        =>  Input::get('active'),
            'sidx'          =>  Input::get('sidx'),
            'sord'          =>  Input::get('sord'),
            'limit'         =>  empty(Input::get('rows')) ? 10 : Input::get('rows'),
            'page'          =>  empty(Input::get('page')) ? 1 : Input::get('page')
        );

        $modules = $mod->getFiltered($search);
        $modulesCount = $mod->getFiltered($search, true);

        if($modulesCount == 0) {
            $numPages = 0;
        } else {
            if($modulesCount % $search['limit'] > 0) {
                $numPages = ($modulesCount - ($modulesCount % $search['limit'])) / $search['limit'] + 1;
            } else {
                $numPages = $modulesCount / $search['limit'];
            }
        }

        $toReturn = array(
            'rows'      =>  array(),
            'records'   =>  $modulesCount,
            'page'      =>  $search['page'],
            'total'     =>  $numPages
        );

        $isGrid = Input::get('is_grid', false); // Checking if the caller is jqGrid -> if yes, we add actions to the response..

        // Adding core module too..
        if($search['page'] == 1) {
            $toReturn['rows'][] = array(
                'id'    =>  -1,
                'cell'  =>  array(
                    "",
                    "Core module",
                    url("/"),
                    "Yes"
                )
            );
        }

        foreach($modules as $module) {
            $actions = "";
            if($isGrid) {
                if($max_permission == 1) {
                    $toolTipTitle = "Activate";
                    $toolTip = "fa-check";
                    if(!empty($module->is_active)) {
                        $toolTipTitle = "Deactivate";
                        $toolTip = "fa-ban";
                    }
                    $actions .= "<button class='btn btn-default btn-xs clickMeModule' title='".$toolTipTitle."' ng-click='vm.activateDeactivateModule(".$module->id.", \"".$toolTipTitle."\")'><i class='fa ".$toolTip."'></i></button>";
                }
            } else {
                $actions = $module->id;
            }
            $toReturn['rows'][] = array(
                'id'    =>  $module->id,
                'cell'  =>  array(
                    $actions,
                    $module->name,
                    $module->base_url,
                    empty($module->is_active) ? "No" : "Yes"
                )
            );
        }

        return response(json_encode($toReturn), 200);
    }

    public function getModulePages(ModulePage $page, Request $req) {
    	$max_permission = $req->get('max_permission');
        $search = array(
            'name'          =>  Input::get('name'),
            'active'		=>	Input::get('active'),
            'module_id'     =>  Input::get('id'),
            'with_hidden'   =>  Input::get('with_hidden', 0),
    		'sidx'      	=>  Input::get('sidx'),
    		'sord'			=>	Input::get('sord'),
    		'limit'     	=>  empty(Input::get('rows')) ? 10 : Input::get('rows'),
            'page'      	=>  empty(Input::get('page')) ? 1 : Input::get('page')
    	);

        $modulePages = $page->getFiltered($search);
    	$modulePagesCount = $page->getFiltered($search, true);

    	if($modulePagesCount == 0) {
            $numPages = 0;
        } else {
            if($modulePagesCount % $search['limit'] > 0) {
                $numPages = ($modulePagesCount - ($modulePagesCount % $search['limit'])) / $search['limit'] + 1;
            } else {
                $numPages = $modulePagesCount / $search['limit'];
            }
        }

        $toReturn = array(
            'rows'      =>  array(),
            'records'   =>  $modulePagesCount,
            'page'      =>  $search['page'],
            'total'     =>  $numPages
        );

        $isGrid = Input::get('is_grid', false); // Checking if the caller is jqGrid -> if yes, we add actions to the response..

        foreach($modulePages as $modulePage) {
            $actions = "";
            if($isGrid) {
                if($max_permission == 1) {
                    $toolTipTitle = "Activate";
                    $toolTip = "fa-check";
                    if(!empty($modulePage->is_active)) {
                        $toolTipTitle = "Deactivate";
                        $toolTip = "fa-ban";
                    }
                    $actions .= "<button class='btn btn-default btn-xs clickMeModulePage' title='".$toolTipTitle."' ng-click='vm.activateDeactivatePage(".$modulePage->id.", \"".$toolTipTitle."\")'><i class='fa ".$toolTip."'></i></button>";
                }
            } else {
                $actions = $modulePage->id;
            }
        	$toReturn['rows'][] = array(
        		'id'	=>	$modulePage->id,
        		'cell'	=> 	array(
        			$actions,
        			$modulePage->name,
        			$modulePage->code,
        			!empty($modulePage->is_active) ? true : false,
        			!empty($modulePage->module_id) ? $modulePage->module_name : "Core"
        		)
        	);
        }

        return response(json_encode($toReturn), 200);
    }

    public function activateDeactivatePage(ModulePage $page) {
        $id = Input::get('id');
        $page = $page->findOrFail($id);

        if(!empty($page->is_active)) {
            $page->is_active = null;
        } else {
            $page->is_active = 1;
        }

        $page->save();

        $toReturn['success'] = 1;
        return response(json_encode($toReturn), 200);
    }

    public function activateDeactivateModule(Module $mod) {
        $id = Input::get('id');
        $mod = $mod->findOrFail($id);

        if(!empty($mod->is_active)) {
            $mod->is_active = null;
        } else {
            $mod->is_active = 1;
        }

        $mod->save();

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
        // Saving module..
        $mod->name = Input::get('name');
        $mod->code = Input::get('code');
        $mod->handshake_token = $opt->generateNewSecretToken($mod->code);
        $mod->base_url = Input::get('base_url');
        $mod->save();

        // Adding module pages..
        $pagesUnserialized = json_decode(Input::get('pages'));
        foreach($pagesUnserialized as $page) {
            $pageTmp = new ModulePage();
            $pageTmp->module_id = $mod->id;
            $pageTmp->name = $page->name;
            $pageTmp->code = $page->code;
            $pageTmp->module_link = $page->module_link;
            $pageTmp->icon = $page->icon;
            $pageTmp->is_active = 1;
            $pageTmp->save();
        }

        $toReturn['success'] = 1;
        $toReturn['handshake_token'] = $mod->handshake_token;
        return response(json_encode($toReturn), 200);
    }
}
