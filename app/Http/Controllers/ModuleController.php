<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\ModulePage;

use Input;

class ModuleController extends Controller
{
    public function getModulePages(ModulePage $page) {
    	$search = array(
            'name'          =>  Input::get('name'),
            'active'		=>	Input::get('active'),
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
                $actions .= "<button class='btn btn-default btn-xs clickMeWg' title='Edit' ng-click='vm.editWorkGroup(".$modulePage->id.")'><i class='fa fa-pencil'></i></button>";
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
}
