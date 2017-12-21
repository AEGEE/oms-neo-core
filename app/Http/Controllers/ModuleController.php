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
/*
{
    "rows": [
        {
            "id": -1,
            "cell": [
                "",
                "Core module",
                "http://localhost",
                "Yes"
            ]
        },
        {
            "id": 1,
            "cell": [
                1,
                "Alastair",
                "/services/alastair",
                "No"
            ]
        },
        {
            "id": 2,
            "cell": [
                2,
                "OMS Events",
                "/services/omsevents",
                "No"
            ]
        }
    ],
    "records": 2,
    "page": 1,
    "total": 1
}
 */
    public function getModules(Module $mod, Request $req) {
        $client = new \GuzzleHttp\Client();
        // $res = $client->get('http://localhost:7000/services');
        $res = $client->get('http://omsserviceregistry:7000/services');
        $services = json_decode($res->getBody());
        // echo $res->getStatusCode(); // 200

        $modules = $services->data;
        //dump($modules);
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

        $isGrid = Input::get('is_grid', true); // Checking if the caller is jqGrid -> if yes, we add actions to the response..

        foreach($modules as $key=>$module) {
            $actions = "";
            if($isGrid) {
                    $toolTipTitle = "Activate"; //TODO
                    $toolTip = "fa-question";
                    $actions .= "<button class='btn btn-default btn-xs clickMeModule' title='".$toolTipTitle."' ng-click='vm.activateDeactivateModule(".$key.", \"".$toolTipTitle."\")'><i class='fa ".$toolTip."'></i></button>";
            } else {
                $actions = $key;
            }
            $toReturn['rows'][] = array(
                'id'    =>  $key,
                'cell'  =>  array(
                    $actions,
                    $module->name,
                    empty($module->url) ? "-" : $module->url,
                    "Probably"
                )
            );
        }

        return response(json_encode($toReturn), 200);
    }

/*
{
"rows":[
  {
     "id":7,
     "cell":[
        "<button class='btn btn-default btn-xs clickMeModulePage' title='Deactivate' ng-click='vm.activateDeactivatePage(7, \"Deactivate\")'><i class='fa fa-ban'><\/i><\/button>",
        "All events",
        "events",
        true,
        "OMS Events"
     ]
  },
  {
     "id":8,
     "cell":[
        "<button class='btn btn-default btn-xs clickMeModulePage' title='Deactivate' ng-click='vm.activateDeactivatePage(8, \"Deactivate\")'><i class='fa fa-ban'><\/i><\/button>",
        "Event admin",
        "eventadmin",
        true,
        "OMS Events"
     ]
  }
],
"records":2,
"page":"1",
"total":1
}


 */
    public function getModulePages(ModulePage $page, Request $req) {
        $client = new \GuzzleHttp\Client();
        // $res = $client->get('http://localhost:7000/services');
        $res = $client->get('http://omsserviceregistry:7000/services');
        $services = json_decode($res->getBody());
        // echo $res->getStatusCode(); // 200

        $modules = $services->data;
        $pages = array();
        foreach($modules as $module) {
            if (!empty($module->modules)) {
                $pages = array_merge($pages, $module->modules->pages);
            }
        }
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

        $isGrid = Input::get('is_grid', true); // Checking if the caller is jqGrid -> if yes, we add actions to the response..

        foreach($pages as $key=>$page) {
            $actions = "";
            if($isGrid) {
                $toolTipTitle = "Activate"; //TODO
                $toolTip = "fa-question";
                // $actions .= "<button class='btn btn-default btn-xs clickMeModulePage' title='".$toolTipTitle."' ng-click='vm.activateDeactivatePage(".$key.", \"".$toolTipTitle."\")'><i class='fa ".$toolTip."'></i></button>";
            } else {
                $actions = $key;
            }
        	$toReturn['rows'][] = array(
        		'id'	=>	$key,
        		'cell'	=> 	array(
        			$actions,
        			$page->name,
        			$page->code,
        			"Could be",
        			$page->name
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
        return response()->notImplemented();
    }
}
