<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Department;
use App\Models\EmailTemplate;
use App\Models\Fee;
use App\Models\FeeUser;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;

use Excel;
use Hash;
use Input;
use Mail;

class UserController extends Controller
{
    public function getUsers(User $user) {
    	$search = array(
            'name'          	=>  Input::get('name'),
            'date_of_birth'		=>	Input::get('date_of_birth'),
            'contact_email'		=>	Input::get('contact_email'),
            'gender'			=>	Input::get('gender'),
            'antenna_id'		=>	Input::get('antenna_id'),
            'department_id'		=>	Input::get('department_id'),
            'internal_email'	=>	Input::get('internal_email'),
            'studies_type_id'	=>	Input::get('studies_type_id'),
            'studies_field_id'	=>	Input::get('studies_field_id'),
            'status'			=>	Input::get('status'),
    		'sidx'      		=>  Input::get('sidx'),
    		'sord'				=>	Input::get('sord'),
    		'limit'     		=>  empty(Input::get('rows')) ? 10 : Input::get('rows'),
            'page'      		=>  empty(Input::get('page')) ? 1 : Input::get('page')
    	);

        $export = Input::get('export', false);
        if($export) {
            $search['noLimit'] = true;
        }

        $users = $user->getFiltered($search);

        if($export) {
            Excel::create('users', function($excel) use ($users) {
                $excel->sheet('users', function($sheet) use ($users) {
                    $sheet->loadView('excel_templates.users')->with("users", $users);
                });
            })->export('xlsx');
            return;
        }

    	$usersCount = $user->getFiltered($search, true);
    	if($usersCount == 0) {
            $numPages = 0;
        } else {
            if($usersCount % $search['limit'] > 0) {
                $numPages = ($usersCount - ($usersCount % $search['limit'])) / $search['limit'] + 1;
            } else {
                $numPages = $usersCount / $search['limit'];
            }
        }

        $toReturn = array(
            'rows'      =>  array(),
            'records'   =>  $usersCount,
            'page'      =>  $search['page'],
            'total'     =>  $numPages
        );

        $isGrid = Input::get('is_grid', false); // Checking if the caller is jqGrid -> if yes, we add actions to the response..

        foreach($users as $userX) {
            $actions = "";
            if($isGrid) {
                // $actions .= "<button class='btn btn-default btn-xs clickMeUser' title='Edit' ng-click='vm.editUser(".$userX->id.", \"userModal\")'><i class='fa fa-pencil'></i></button>"; // Temporary disabled due to some angular bugs
                if($userX->status == 2) {
                    $actions .= "<button class='btn btn-default btn-xs clickMeUser' title='Activate user' ng-click='vm.editUser(".$userX->id.", \"activateUserModal\")'><i class='fa fa-check'></i></button>"; // Temporary disabled due to some angular bugs
                }
            } else {
                $actions = $userX->id;
            }
        	$toReturn['rows'][] = array(
        		'id'	=>	$userX->id,
        		'cell'	=> 	array(
        			$actions,
        			$userX->first_name." ".$userX->last_name,
        			$userX->date_of_birth->format('d/m/Y'),
        			$userX->contact_email,
        			$userX->gender_text,
        			$userX->antenna->name,
        			empty($userX->department_id) ? "-" : $userX->department->name,
        			$userX->internal_email,
        			$userX->studyField->name,
        			$userX->studyType->name,
        			$userX->status
        		)
        	);
        }

        return response(json_encode($toReturn), 200);
    }

    public function getUser(User $user) {
        $id = Input::get('id');
        $user = $user->findOrFail($id);

        $toReturn['success'] = 1;
        $toReturn['user'] = $user;
        return response(json_encode($toReturn), 200);
    }

    public function activateUser(User $user, Role $role, Fee $fee, EmailTemplate $tpl) {
        // User..
        $id = Input::get('id');
        $user = $user->findOrFail($id);

        if(!empty($user->activated_at)) {
            $toReturn['success'] = 0;
            $toReturn['message'] = "User already activated!";
            return response(json_encode($toReturn), 200);
        }

        $departmentId = Input::get('department_id');
        $departmentCheck = Department::findOrFail($departmentId);

        $user->department_id = $departmentId;
        $user->seo_url = $user->generateSeoUrl();
        $user->activated_at = date('Y-m-d H:i:s');

        $userPass = $user->generateRandomPassword();

        $oAuthActive = false;
        if($oAuthActive) {
            $username = ""; // Todo..
            // Todo.. activate account with oauth..
        } else {
            $username = $user->contact_email;
            $user->password = Hash::make($userPass);
        }

        $user->save();

        $rolesCache = $role->getCache();
        $feesCache = $fee->getCache();

        // Now for roles..
        $roles = Input::get('roles');
        foreach($roles as $key => $val) {
            if(!$val || !isset($rolesCache[$key])) { // Role set as false or does not exist..
                continue;
            }
            $tmpRole = new UserRole();
            $tmpRole->user_id = $user->id;
            $tmpRole->role_id = $key;
            $tmpRole->save();
        }

        // Now for fees..
        $fees = Input::get('fees');
        $feesPaid = Input::get('feesPaid');
        foreach($fees as $key => $val) {
            if(!$val || !isset($feesCache[$key])) { // Fee set as false or does not exist..
                continue;
            }

            $tmpFee = new FeeUser();
            $tmpFee->fee_id = $key;
            $tmpFee->user_id = $user->id;
            if(isset($feesPaid[$key])) {
                $tmpFee->date_paid = $feesPaid[$key];
            } else {
                $tmpFee->date_paid = date('Y-m-d');
            }

            $tmpFee->expiration_date = $fee->getFeeEndTime($feesCache[$key]['availability'], $feesCache[$key]['availability_unit'], $tmpFee->date_paid);
            $tmpFee->save();

        }

        // Email user with all data..
        $tpl = $tpl->where('code', 'account_activated')->first();
        $toReplace = array(
            '{fullname}'        =>  $user->first_name." ".$user->last_name,
            '{username}'        =>  $username,
            '{password}'        =>  $userPass,
            '{link}'            =>  url('/')
        );

        $addToView = $tpl->prepareContentForView($toReplace);

        Mail::send('emails.email2', $addToView, function($message) use($user, $addToView) {
            $message->from($addToView['globals']['email_sender'], $addToView['globals']['app_name']);
            $message->to($user->contact_email);
            $message->subject($addToView['title']);
        });

        $toReturn['success'] = 1;
        return response(json_encode($toReturn), 200);
    }
}
