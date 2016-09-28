<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\SaveWorkingGroupRequest;

use App\Models\Department;

use Excel;
use Input;

class DepartmentController extends Controller
{
    public function getDepartments(Department $dep, Request $req) {
        $max_permission = $req->get('max_permission');
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

        $departments = $dep->getFiltered($search);

        if($export) {
            Excel::create('departments', function($excel) use ($departments) {
                $excel->sheet('departments', function($sheet) use ($departments) {
                    $sheet->loadView('excel_templates.departments')->with("departments", $departments);
                });
            })->export('xlsx');
            return;
        }

    	$departmentsCount = $dep->getFiltered($search, true);
    	if($departmentsCount == 0) {
            $numPages = 0;
        } else {
            if($departmentsCount % $search['limit'] > 0) {
                $numPages = ($departmentsCount - ($departmentsCount % $search['limit'])) / $search['limit'] + 1;
            } else {
                $numPages = $departmentsCount / $search['limit'];
            }
        }

        $toReturn = array(
            'rows'      =>  array(),
            'records'   =>  $departmentsCount,
            'page'      =>  $search['page'],
            'total'     =>  $numPages
        );

        $isGrid = Input::get('is_grid', false); // Checking if the caller is jqGrid -> if yes, we add actions to the response..

        foreach($departments as $department) {
            $actions = "";
            if($isGrid) {
                if($max_permission == 1) {
                    $actions .= "<button class='btn btn-default btn-xs clickMeDep' title='Edit' ng-click='vm.editDepartment(".$department->id.")'><i class='fa fa-pencil'></i></button>";
                }
            } else {
                $actions = $department->id;
            }
        	$toReturn['rows'][] = array(
        		'id'	=>	$department->id,
        		'cell'	=> 	array(
        			$actions,
        			$department->name,
        			$department->description
        		)
        	);
        }

        return response(json_encode($toReturn), 200);
    }

    public function saveDepartment(Department $dep, SaveWorkingGroupRequest $req) {
        $id = Input::get('id');
        if(!empty($id)) {
            $dep = $dep->findOrFail($id);
        }

        $dep->name = Input::get('name');
        $dep->description = Input::get('description');
        $dep->save();

        $toReturn['success'] = 1;
        return response(json_encode($toReturn), 200);
    }

    public function getDepartment(Department $dep) {
        $id = Input::get('id');
        $dep = $dep->findOrFail($id);

        $toReturn['success'] = 1;
        $toReturn['department'] = $dep;
        return response(json_encode($toReturn), 200);
    }
}
