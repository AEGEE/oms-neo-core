<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\SaveWorkingGroupRequest;

use App\Models\WorkingGroup;

use Excel;
use Input;

class WorkingGroupController extends Controller
{
    public function getWorkingGroups(WorkingGroup $wg) {
    	$search = array(
            'name'          =>  Input::get('name'),
    		'sidx'      	=>  Input::get('sidx'),
    		'sord'			=>	Input::get('sord'),
    		'limit'     	=>  empty(Input::get('rows')) ? 10 : Input::get('rows'),
            'page'      	=>  empty(Input::get('page')) ? 1 : Input::get('page')
    	);

        $export = Input::get('export', false);
        if($export) {
            $search['noLimit'] = true;
        }

        $workGroups = $wg->getFiltered($search);

        if($export) {
            Excel::create('workGroups', function($excel) use ($workGroups) {
                $excel->sheet('workGroups', function($sheet) use ($workGroups) {
                    $sheet->loadView('excel_templates.workGroups')->with("workGroups", $workGroups);
                });
            })->export('xlsx');
            return;
        }

    	$workGroupsCount = $wg->getFiltered($search, true);
    	if($workGroupsCount == 0) {
            $numPages = 0;
        } else {
            if($workGroupsCount % $search['limit'] > 0) {
                $numPages = ($workGroupsCount - ($workGroupsCount % $search['limit'])) / $search['limit'] + 1;
            } else {
                $numPages = $workGroupsCount / $search['limit'];
            }
        }

        $toReturn = array(
            'rows'      =>  array(),
            'records'   =>  $workGroupsCount,
            'page'      =>  $search['page'],
            'total'     =>  $numPages
        );

        $isGrid = Input::get('is_grid', false); // Checking if the caller is jqGrid -> if yes, we add actions to the response..

        foreach($workGroups as $workGroup) {
            $actions = "";
            if($isGrid) {
                $actions .= "<button class='btn btn-default btn-xs clickMeWg' title='Edit' ng-click='vm.editWorkGroup(".$workGroup->id.")'><i class='fa fa-pencil'></i></button>";
            } else {
                $actions = $workGroup->id;
            }
        	$toReturn['rows'][] = array(
        		'id'	=>	$workGroup->id,
        		'cell'	=> 	array(
        			$actions,
        			$workGroup->name,
        			$workGroup->description
        		)
        	);
        }

        return response(json_encode($toReturn), 200);
    }

    public function saveWorkGroup(WorkingGroup $wg, SaveWorkingGroupRequest $req) {
        $id = Input::get('id');
        if(!empty($id)) {
            $wg = $wg->findOrFail($id);
        }

        $wg->name = Input::get('name');
        $wg->description = Input::get('description');
        $wg->save();

        $toReturn['success'] = 1;
        return response(json_encode($toReturn), 200);
    }

    public function getWorkGroup(WorkingGroup $wg) {
        $id = Input::get('id');
        $wg = $wg->findOrFail($id);

        $toReturn['success'] = 1;
        $toReturn['workgroup'] = $wg;
        return response(json_encode($toReturn), 200);
    }
}
