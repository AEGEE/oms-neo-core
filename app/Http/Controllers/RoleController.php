<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\SaveRoleRequest;

use App\Models\ModulePage;
use App\Models\Role;
use App\Models\RoleModulePage;

use Excel;
use Input;

class RoleController extends Controller
{
    public function getRoles(Role $role, ModulePage $modPage, Request $req) {
        $max_permission = $req->get('max_permission');
    	$modPageCache = $modPage->getModulePagesCache();

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

        $roles = $role->getFiltered($search);

        if($export) {
            Excel::create('roles', function($excel) use ($roles) {
                $excel->sheet('roles', function($sheet) use ($roles) {
                    $sheet->loadView('excel_templates.roles')->with("roles", $roles);
                });
            })->export('xlsx');
            return;
        }

    	$rolesCount = $role->getFiltered($search, true);
    	if($rolesCount == 0) {
            $numPages = 0;
        } else {
            if($rolesCount % $search['limit'] > 0) {
                $numPages = ($rolesCount - ($rolesCount % $search['limit'])) / $search['limit'] + 1;
            } else {
                $numPages = $rolesCount / $search['limit'];
            }
        }

        $toReturn = array(
            'rows'      =>  array(),
            'records'   =>  $rolesCount,
            'page'      =>  $search['page'],
            'total'     =>  $numPages
        );

        $isGrid = Input::get('is_grid', false); // Checking if the caller is jqGrid -> if yes, we add actions to the response..

        foreach($roles as $roleX) {
            $actions = "";
            if($isGrid) {
                if(empty($roleX->system_role) && $max_permission == 1) {
                    $actions .= "<button class='btn btn-default btn-xs clickMeRole' title='Edit' ng-click='vm.editRole(".$roleX->id.")'><i class='fa fa-pencil'></i></button>";
                }
            } else {
                $actions = $roleX->id;
            }

            $accessToPages = "";
            if(!$isGrid) {
            	$accessToPages = array();
            }

            foreach($roleX->roleModulePages as $page) {
            	$accessLevel = empty($page->permission_level) ? "Read-only" : "Read / Write";
            	if(!$isGrid) {
            		$accessToPages[] = array(
            			'id'			=> 	$page->module_page_id,
            			'page'			=>	$modPageCache[$page->module_page_id],
            			'access_level' 	=> 	$accessLevel
            		);
            	} else {
            		$accessToPages .= "<li>".$modPageCache[$page->module_page_id].": ".$accessLevel."</li>";
            	}
            }

            if($isGrid) {
            	$accessToPages = "<ul>".$accessToPages."</ul>";
            }

        	$toReturn['rows'][] = array(
        		'id'	=>	$roleX->id,
        		'cell'	=> 	array(
        			$actions,
        			$roleX->name,
        			$accessToPages,
                    empty($roleX->system_role) ? 'User defined' : "System"
        		)
        	);
        }

        return response(json_encode($toReturn), 200);
    }

    public function saveRole(Role $role, RoleModulePage $rolePage, SaveRoleRequest $req) {
    	$id = Input::get('id');
        if(!empty($id)) {
            $role = $role->findOrFail($id);
            if(!empty($role->system_role)) {
                $toReturn['success'] = 0;
                $toReturn['message'] = "Cannot edit system roles!";
                return response(json_encode($toReturn), 200);
            }

            $rolePage->where('role_id', $id)->delete();
        }

        $role->name = Input::get('name');
        $role->save();

        $modules = Input::get('module');
        $modulePermissions = Input::get('moduleAccess');

        foreach($modules as $key => $value) {
        	if(!$value) {
        		continue;
        	}

        	$rolePageTmp = new RoleModulePage();
        	$rolePageTmp->role_id = $role->id;
        	$rolePageTmp->module_page_id = $key;
        	if(isset($modulePermissions[$key]) && $modulePermissions[$key] == 1) {
        		// Write access..
        		$rolePageTmp->permission_level = 1;
        	}
        	$rolePageTmp->save();
        }

        $toReturn['success'] = 1;
        return response(json_encode($toReturn), 200);
    }

    public function getRole(Role $role, RoleModulePage $rolePage) {
    	$id = Input::get('id');
    	$role = $role->findOrFail($id);

    	$toReturn['success'] = 1;

    	$moduleArr = array();
    	$modulePermissionArr = array();

    	$rolePages = $rolePage->where('role_id', $role->id)->get();
    	foreach($rolePages as $rolePageX) {
    		$moduleArr[$rolePageX->module_page_id] = true;
    		$permission = empty($rolePageX->permission_level) ? 0 : 1;
    		$modulePermissionArr[$rolePageX->module_page_id] = $permission;
    	}

    	$toReturn['role'] = array(
    		'id'			=>	$role->id,
    		'name'			=>	$role->name,
    		'module'		=>	$moduleArr,
    		'moduleAccess'	=>	$modulePermissionArr
    	);

    	return response(json_encode($toReturn), 200);
    }
}
