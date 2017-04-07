<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\AddBoardPositionRequest;
use App\Http\Requests\AddFeesRequest;
use App\Http\Requests\AddRoleRequest;
use App\Http\Requests\AddWorkingGroupRequest;
use App\Http\Requests\ChangeEmailRequest;
use App\Http\Requests\ChangePasswordRequest;

use App\Models\Body;
use App\Models\Auth;
use App\Models\BoardMember;
use App\Models\Country;
use App\Models\Department;
use App\Models\EmailTemplate;
use App\Models\Fee;
use App\Models\FeeMember;
use App\Models\News;
use App\Models\Role;
use App\Models\Member;
use App\Models\MemberRole;
use App\Models\MemberWorkingGroup;
use App\Models\WorkingGroup;

use App\Repositories\RolesRepository as Repo;

use Excel;
use File;
use Hash;
use Image;
use Input;
use Mail;
use Response;
use Session;

class MemberController extends Controller
{
    public function getMembers(Member $member, Request $req, Repo $repo) {
        $max_permission = $req->get('max_permission');
    	$user = $req->get('user');
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

        $members = Member::all();
        Repo::syncRolesForAll($members, $user);

        return $members;

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
                if($userX->status == 2 && $max_permission == 1) {
                    $actions .= "<button class='btn btn-default btn-xs clickMeUser' title='Activate user' ng-click='vm.editUser(".$userX->id.", \"activateUserModal\")'><i class='fa fa-check'></i></button>";
                } elseif($userX->status != 2 && $userX->id != $userData->id) {
                    $actions .= "<button class='btn btn-default btn-xs clickMeUser' title='View user profile' ng-click='vm.visitProfile(\"".$userX->seo_url."\")'><i class='fa fa-search'></i></button>";
                }
            } else {
                $actions = $userX->id;
            }
        	$toReturn['rows'][] = array(
        		'id'	=>	$userX->id,
        		'cell'	=> 	array(
        			$actions,
        			$userX->last_name." ".$userX->first_name,
        			$userX->date_of_birth->format('d/m/Y'),
        			$userX->contact_email,
        			$userX->gender_text,
        			$userX->antenna->name,
        			empty($userX->department_id) ? "-" : $userX->department->name,
        			$userX->internal_email,
        			$userX->studyField->name,
        			$userX->studyType->name,
        			$userX->status,
                    $userX->seo_url
        		)
        	);
        }

        return response(json_encode($toReturn), 200);
    }

    public function getMember(Request $req, Member $member) {
      $member->syncRoles($req->get('user'));
      dd($member->bodyRoles()->get());
      return response($member, 200);
    }

    public function activateUser(Member $member, Role $role, Fee $fee, EmailTemplate $tpl, Request $req) {
        $currentUser = $req->get('userData');
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

        $oAuthActive = $this->isOauthDefined();
        if($oAuthActive) {
            $domain = $this->getOAuthAllowedDomain();
            $username = $user->seo_url."@".$domain;

            $success = $user->oAuthCreateAccount(
                $this->getOAuthProvider(),
                $this->getDelegatedAdmin(),
                $this->getOauthCredentials($currentUser['id']),
                $domain,
                $user->seo_url,
                $userPass
            );

            $user->internal_email = $username;

            if($success !== true) {
                die("oAuth problem! Error code:".$success);
            }
        } else {
            $username = $user->contact_email;
            $user->password = Hash::make($userPass);
        }

        $user->save();

        $rolesCache = $role->getCache();
        $feesCache = $fee->getCache();

        // Now for roles..
        $roles = Input::get('roles', array());
        foreach($roles as $key => $val) {
            if(!$val || !isset($rolesCache[$key])) { // Role set as false or does not exist..
                continue;
            }
            $tmpRole = new MemberRole();
            $tmpRole->member_id = $user->id;
            $tmpRole->role_id = $key;
            $tmpRole->save();
        }

        // Now for fees..
        $fees = Input::get('fees', array());
        $feesPaid = Input::get('feesPaid');
        foreach($fees as $key => $val) {
            if(!$val || !isset($feesCache[$key])) { // Fee set as false or does not exist..
                continue;
            }

            $tmpFee = new FeeMember();
            $tmpFee->fee_id = $key;
            $tmpFee->member_id = $user->id;
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

    public function getMemberByToken(Auth $auth) {
        $token = Input::get('token');
        if(empty($token)) {
            $toReturn['success'] = 0;
            return response(json_encode($toReturn), 200);
        }

        $now = date('Y-m-d H:i:s');
        $userData = $auth->with('user')->where('token_generated', $token)
                        ->where(function($query) use($now) {
                            $query->where('expiration', '>', $now)
                                    ->orWhereNull('expiration');
                        })
                        ->firstOrFail();
        $antenna = Body::findOrFail($userData->user->antenna_id);

        $toReturn['success'] = 1;
        $toReturn['user'] = $userData->user;
        $toReturn['user']['antenna'] = $antenna->name;
        return response(json_encode($toReturn), 200);
    }

    public function getMemberProfile(Member $member, WorkingGroup $wg, Department $dep, Role $role, Fee $fee, MemberRole $userRole, Request $req) {
        $isOauthDefined = $this->isOauthDefined();
        $user = $req->get('user');

        $url = Input::get('seo_url', $user->seo_url);
        $isUi = Input::get('is_ui', false);
        $member = $member->with('studyField', 'studyType')->where('seo_url', $url)->firstOrFail();
        $member->syncRoles($user);

        $id = $user->id;
        $toReturn['success'] = 1;
        //$country = Country::find($user->bodies->first()->country_id);
        $toReturn['user'] = array(
            'id'                =>  $user->id,
            'fullname'          =>  $user->first_name." ".$user->last_name,
            'antenna'           =>  json_encode($member->getBodiesQuery()->pluck('name')),
            'antenna_city'      =>  'not supported yet',
            'country'           =>  'not supported yet',
            'department'        =>  !empty($user->department) ? $user->department->name : "-",
            'date_of_birth'     =>  $member->date_of_birth->format('Y-m-d'),
            'gender'            =>  $user->genderText,
            'university'        =>  $user->university,
            'studies'           =>  $member->studyField->name." (".$member->studyType->name.")",
            'city'              =>  $user->city,
            'bio'               =>  !empty($user->other) ? $user->other : "No bio available",
            'rank'              =>  'Member',
            'email'             =>  $member->getEmailAddress(),
            'activated_at'      =>  $user->activated_at, //->format('Y-m-d'),
            'status'            =>  $user->status_text,
            'suspended_for'     =>  $user->suspended_reason,
            'is_boardmember'    =>  0
        );

        $toReturn['workingGroups'] = array();
        $wgs = $wg->getMemberWorkingGroups($id);
        foreach ($wgs as $work) {
            $toReturn['workingGroups'][] = array(
                'id'        =>  $work->id,
                'name'      =>  $work->name,
                'period'    =>  $work->getPeriod()
            );
        }

        $toReturn['board_positions'] = array();
        $isCurrentBoardMember = false;
        $boards = $dep->getMemberBoardMemberships($id);
        foreach ($boards as $membership) {
            $toReturn['board_positions'][] = array(
                'id'        =>  $membership->id,
                'name'      =>  $membership->name,
                'period'    =>  $membership->getPeriod()
            );
            if(!$isCurrentBoardMember) {
                $isCurrentBoardMember = $membership->isActiveMembership();
            }
        }

        $toReturn['roles'] = array();
        $roles = $role->getMemberRoles($id);
        foreach ($roles as $roleX) {
            $toReturn['roles'][] = array(
                'id'        =>  $roleX->id,
                'name'      =>  $roleX->name,
            );
        }

        $toReturn['fees_paid'] = array();
        $fees = $fee->getMemberFees($id);
        foreach ($fees as $userFee) {
            $toReturn['fees_paid'][] = array(
                'id'        =>  $userFee->id,
                'name'      =>  $userFee->name,
                'period'    =>  $userFee->getPeriod()
            );
        }

        // Determining highest rank..
        if(!empty($user->is_superadmin)) {
            $toReturn['user']['rank'] = 'Super Admin';
            $toReturn['user']['is_boardmember'] = 1;
        } else if($isCurrentBoardMember) {
            $toReturn['user']['rank'] = "Board member";
            $toReturn['user']['is_boardmember'] = 1;
        }

        if(!empty($user->is_suspended)) {
            $toReturn['user']['rank'] = 'Suspended';
        }

        $userMaxLevelOfEditing = $userRole->getMaxPermissionLevelForRole('users', $user->id);

        $toReturn['active_fields'] = array(
            'change_avatar'         =>  ($id == $user->id) ? true : false,
            'change_password'       =>  ($id == $user->id && !$isOauthDefined) ? true : false,
            'change_email'          =>  ($id == $user->id && !$isOauthDefined) ? true : false,
            'change_bio'            =>  ($id == $user->id) ? true : false,
            'addEditStuff'          =>  ($user->is_superadmin || $userMaxLevelOfEditing == 1) ? true : false,
            'account_info'          =>  ($user->is_superadmin || $userMaxLevelOfEditing == 1) ? true : false,
            'suspend_account'       =>  ($user->status == 1 && $id != $user->id) ? true : false,
            'unsuspend_account'     =>  ($user->status == 3 && $id != $user->id) ? true : false,
            'impersonate'           =>  ($id != $user->id) ? true : false,
            'suspended'             =>  empty($user->suspended_reason) ? false : true,
            'work_groups'           =>  (count($toReturn['workingGroups']) > 0 || $user->is_superadmin || $userMaxLevelOfEditing == 1) ? true : false,
            'board_positions'       =>  (count($toReturn['board_positions']) > 0 || $user->is_superadmin || $userMaxLevelOfEditing == 1) ? true : false,
            'role'                  =>  (count($toReturn['roles']) > 0 || $user->is_superadmin || $userMaxLevelOfEditing == 1) ? true : false,
        );


        return response(json_encode($toReturn), 200);
    }

    public function setBoardPosition(BoardMember $bm, AddBoardPositionRequest $req) {
        $bm->member_id = Input::get('member_id');
        $bm->department_id = Input::get('department_id');
        $bm->start_date = Input::get('start_date');
        $bm->end_date = Input::get('end_date');
        $bm->save();

        $toReturn['success'] = 1;
        return response(json_encode($toReturn), 200);
    }

    public function addMemberRoles(Role $role, AddRoleRequest $req) {
        $id = Input::get('member_id');
        $rolesCache = $role->getCache();

        $roles = Input::get('roles');
        foreach ($roles as $key => $val) {
            if(!$val || !isset($rolesCache[$key])) {
                continue;
            }

            MemberRole::firstOrCreate([
                'member_id'   =>  $id,
                'role_id'   =>  $key
            ]);
        }

        $toReturn['success'] = 1;
        return response(json_encode($toReturn), 200);
    }

    public function addFeesToUser(Fee $fee, AddFeesRequest $req) {
        $id = Input::get('member_id');

        $feesCache = $fee->getCache();

        $fees = Input::get('fees');
        $feesPaid = Input::get('feesPaid');
        foreach($fees as $key => $val) {
            if(!$val || !isset($feesCache[$key])) { // Fee set as false or does not exist..
                continue;
            }

            $tmpFee = new FeeMember();
            $tmpFee->fee_id = $key;
            $tmpFee->member_id = $id;
            if(isset($feesPaid[$key])) {
                $tmpFee->date_paid = $feesPaid[$key];
            } else {
                $tmpFee->date_paid = date('Y-m-d');
            }

            $tmpFee->expiration_date = $fee->getFeeEndTime($feesCache[$key]['availability'], $feesCache[$key]['availability_unit'], $tmpFee->date_paid);
            $tmpFee->save();

        }

        $toReturn['success'] = 1;
        return response(json_encode($toReturn), 200);
    }

    public function deleteFee(FeeMember $obj) {
        $id = Input::get('id');
        $obj = $obj->findOrFail($id);
        $obj->delete();

        $toReturn['success'] = 1;
        return response(json_encode($toReturn), 200);
    }

    public function deleteRole(MemberRole $obj) {
        $id = Input::get('id');
        $obj = $obj->findOrFail($id);
        $obj->delete();

        $toReturn['success'] = 1;
        return response(json_encode($toReturn), 200);
    }

    public function deleteMembership(BoardMember $obj) {
        $id = Input::get('id');
        $obj = $obj->findOrFail($id);
        $obj->delete();

        $toReturn['success'] = 1;
        return response(json_encode($toReturn), 200);
    }

    public function deleteWorkGroup(MemberWorkingGroup $obj) {
        $id = Input::get('id');
        $obj = $obj->findOrFail($id);
        $obj->delete();

        $toReturn['success'] = 1;
        return response(json_encode($toReturn), 200);
    }

    public function changeEmail(Member $member, ChangeEmailRequest $req) {
        $userData = $req->get('userData');
        $user = $user->findOrFail($userData['id']);
        $user->contact_email = Input::get('email');

        $emailHash = $user->getEmailHash($user->contact_email);
        if($user->checkEmailHash($emailHash, $user->id)) {
            $toReturn['success'] = 0;
            $toReturn['message'] = "Email already exists!";
            return response(json_encode($toReturn), 200);
        }

        $user->save();

        $toReturn['success'] = 1;
        return response(json_encode($toReturn), 200);
    }

    public function changePassword(Member $member, ChangePasswordRequest $req) {
        $userData = $req->get('userData');
        $user = $user->findOrFail($userData['id']);

        $newPassword = Input::get('password');
        $user->password = Hash::make($newPassword);
        $user->save();

        $toReturn['success'] = 1;
        return response(json_encode($toReturn), 200);
    }

    public function editBio(Member $member, Request $req) {
        $userData = $req->get('userData');
        $user = $user->findOrFail($userData['id']);

        $bio = Input::get('bio');
        $user->other = nl2br($bio);
        $user->save();

        $toReturn['success'] = 1;
        return response(json_encode($toReturn), 200);
    }

    public function suspendAccount(Member $member, Request $req) {
        $userData = $req->get('userData');

        $id = Input::get('id');
        $user = $user->findOrFail($id);

        $suspensionReason = Input::get('reason');
        $user->suspendAccount($suspensionReason, $userData->first_name." ".$userData->last_name);

        $toReturn['success'] = 1;
        return response(json_encode($toReturn), 200);
    }

    public function unsuspendAccount(Member $member, Request $req) {
        $userData = $req->get('userData');

        $id = Input::get('id');
        $user = $user->findOrFail($id);

        $user->unsuspendAccount();

        $toReturn['success'] = 1;
        return response(json_encode($toReturn), 200);
    }

    public function impersonateUser(Member $member, Request $req, Auth $auth) {
        $userData = $req->get('userData');
        $xAuthToken = isset($_SERVER['HTTP_X_AUTH_TOKEN']) ? $_SERVER['HTTP_X_AUTH_TOKEN'] : '';

        $id = Input::get('id');
        $user = $user->findOrFail($id);

        $auth = $auth->where('token_generated', $xAuthToken)->firstOrFail();
        $auth->member_id = $id; // Switching token to new user..
        $auth->save();

        $userData = $user->getLoginMemberArray($xAuthToken);

        Session::put('userData', $userData);
        // Mirroring Laravel session data to PHP native session.. For use with angular partials..
        session_start();
        $_SESSION['userData'] = Session::get('userData');
        session_write_close();

        $toReturn['success'] = 1;
        return response(json_encode($toReturn), 200);
    }

    public function addWorkingGroupToUser(Member $member, MemberWorkingGroup $uWg, AddWorkingGroupRequest $req) {
        $id = Input::get('member_id');
        $wgId = Input::get('work_group_id');

        $uWg = $uWg->firstOrCreate([
            'member_id'       =>  $id,
            'work_group_id' =>  $wgId
        ]);

        $start_date = Input::get('start_date');
        $end_date = Input::get('end_date');

        $needsSave = false;
        if(!empty($start_date)) {
            $uWg->start_date = $start_date;
            $needsSave = true;
        }

        if(!empty($end_date)) {
            $uWg->end_date = $end_date;
            $needsSave = true;
        }

        if($needsSave) {
            $uWg->save();
        }

        $toReturn['success'] = 1;
        return response(json_encode($toReturn), 200);
    }

    public function getDashboardData(Member $member, News $news) {
        $toReturn = array();
        $toReturn['userCount'] = $user->whereNotNull('activated_at')->count();
        $newestMembers = $user->with('antenna')->whereNotNull('activated_at')->orderBy('activated_at', 'DESC')->take(12)->get();
        foreach($newestMembers as $member) {
            $toReturn['newestMembers'][] = array(
                'id'        =>  $member->id,
                'fullname'  =>  $member->first_name." ".$member->last_name,
                'local'     =>  $member->antenna->name,
                'seo_url'   =>  $member->seo_url
            );

        }

        $toReturn['latestNews'] = array(
            'rows'      =>  array()
        );
        $search['sidx'] = 'date';
        $search['sord'] = 'desc';
        $search['limit'] = 5;
        $search['page'] = 1;

        $news = $news->getFiltered($search);
        foreach($news as $new) {
            $toReturn['latestNews']['rows'][] = array(
                'id'    =>  $new->id,
                'cell'  =>  array(
                    '',
                    $new->title,
                    $new->content,
                    $new->created_at->format('d/m/Y'),
                    $new->user->first_name." ".$new->user->last_name
                )
            );
        }

        return response(json_encode($toReturn), 200);
    }

    public function uploadUserAvatar(Request $req) {
        $userData = $req->get('userData');

        $allowedExt = array(
            'png', 'jpg', 'jpeg'
        );

        $path = storage_path();
        $baseDir = $path."/userAvatars/";
        if(!file_exists($baseDir)) {
            mkdir($baseDir, 0777, true);
        }
        $tmpPlace = $path."/userAvatarsTmp/";
        if(!file_exists($tmpPlace)) {
            mkdir($tmpPlace, 0777, true);
        }

        $file = $req->file('avatar');

        $filename = $file->getClientOriginalName();
        $extension = explode('.', $filename);
        $extension = strtolower($extension[count($extension)-1]);
        if(!in_array($extension, $allowedExt)) {
            $toReturn['success'] = 0;
            $toReturn['message'] = "Extension not allowed!";
            return response(json_encode($toReturn), 200);
        }

        $file->move($tmpPlace, $filename);
        $tmpIm = Image::make($tmpPlace.$filename);
        $tmpIm->fit(300);
        $tmpIm->save($baseDir.$userData->id.".jpg");

        unlink($tmpPlace.$filename);

        $toReturn['success'] = 1;
        return response(json_encode($toReturn), 200);
    }

    public function getMemberAvatar($avatarId) {
        $fallbackAvatar = storage_path()."/baseFiles/defaultAvatar.jpg";
        $path = storage_path()."/userAvatars/".$avatarId.".jpg";

        if(!File::exists($path)) {
            $file = File::get($fallbackAvatar);
            $type = File::mimeType($fallbackAvatar);
        } else {
            $file = File::get($path);
            $type = File::mimeType($path);
        }

        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);
        return $response;
    }

    public function getMemberById(Member $member) {
        $user = $user->findOrFail(Input::get('id'));
        return json_encode($user);
    }
}
