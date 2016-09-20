<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\AddRecrutmentCampaignRequest;
use App\Http\Requests\SignupRequest;

use App\Models\Department;
use App\Models\EmailTemplate;
use App\Models\Fee;
use App\Models\FeeUser;
use App\Models\RecrutedComment;
use App\Models\RecrutedUser;
use App\Models\RecrutementCampaign;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;

use Hash;
use Input;
use Mail;

class RecrutementController extends Controller
{
    public function getRecrutementCampaigns(RecrutementCampaign $campaign, Request $req) {
        $userData = $req->get('userData');

        $search = array(
            'antenna_id'            =>  Input::get('antenna_id'),
            'start_date'            =>  Input::get('start_date'),
            'end_date'              =>  Input::get('end_date'),
            'sidx'                  =>  Input::get('sidx'),
            'sord'                  =>  Input::get('sord'),
            'limit'                 =>  empty(Input::get('rows')) ? 10 : Input::get('rows'),
            'page'                  =>  empty(Input::get('page')) ? 1 : Input::get('page')
        );

        if($userData['is_superadmin'] != 1) {
            $search['antenna_id'] = $userData['antenna_id'];
        }

        $campaigns = $campaign->getFiltered($search);
        $campaignsCount = $campaign->getFiltered($search, true);
        if($campaignsCount == 0) {
            $numPages = 0;
        } else {
            if($campaignsCount % $search['limit'] > 0) {
                $numPages = ($campaignsCount - ($campaignsCount % $search['limit'])) / $search['limit'] + 1;
            } else {
                $numPages = $campaignsCount / $search['limit'];
            }
        }

        $toReturn = array(
            'rows'      =>  array(),
            'records'   =>  $campaignsCount,
            'page'      =>  $search['page'],
            'total'     =>  $numPages
        );

        $isGrid = Input::get('is_grid', false); // Checking if the caller is jqGrid -> if yes, we add actions to the response..

        foreach($campaigns as $camp) {
            $actions = "";
            if($isGrid) {
                //$actions .= "<button class='btn btn-default btn-xs clickMeFee' title='Edit' ng-click='vm.editFee(".$feeX->id.")'><i class='fa fa-pencil'></i></button>";
                $customFields = "<ul>";
                $fieldsX = json_decode($camp->custom_fields);
                foreach($fieldsX as $field) {
                    $type = $field->type == 1 ? "Text field" : "Text area";
                    $customFields .= "<li>".$field->name.": Type: ".$type."</li>";
                }
                $customFields .= "</ul>";
                $linkX = $camp->link." (".url('/register/'.$camp->link).")";
            } else {
                $actions = $camp->id;
                $customFields = json_decode($camp->custom_fields);
                $linkX = $camp->link;
            }

            $toReturn['rows'][] = array(
                'id'    =>  $camp->id,
                'cell'  =>  array(
                    $actions,
                    $camp->antenna->name,
                    $camp->start_date->format('Y-m-d'),
                    $camp->end_date->format('Y-m-d'),
                    $linkX,
                    $customFields
                )
            );
        }

        return response(json_encode($toReturn), 200);
    }

    public function checkLinkAvailability(RecrutementCampaign $campaign) {
    	$link = Input::get('link');
    	$exists = $campaign->whereLink($link)->count();
		
		$toReturn['exists'] = $exists;
		return json_encode($toReturn);
    }

    public function saveCampaign(AddRecrutmentCampaignRequest $req, RecrutementCampaign $campaign) {
    	$campaign->start_date = Input::get('start_date');
    	$campaign->end_date = Input::get('end_date');
    	$campaign->link = Input::get('link', $campaign->createRandomLink());

    	$userData = $req->get('userData');
    	if($userData['is_superadmin'] == 1) {
    		$campaign->antenna_id = Input::get('antenna_id', $userData['antenna_id']);
    	} else {
			$campaign->antenna_id = $userData['antenna_id'];
    	}

    	$customFields = array();
    	$customFieldsInput = Input::get('customFields');
    	// Reparsing custom fields just to make sure..
    	foreach($customFieldsInput as $field) {
    		if($field['type'] != 1 && $field['type'] != 2) {
    			continue; // Not a valid field type..
    		}

    		$customFields[] = array(
    			'name'	=>	$field['name'],
    			'type'	=>	$field['type']
    		);
    	}

    	$campaign->custom_fields = json_encode($customFields);
    	$campaign->save();

    	$toReturn['success'] = 1;
		return json_encode($toReturn);
    }

    public function checkCampaignExists(RecrutementCampaign $campaign) {
        $now = date('Y-m-d');
        $link = Input::get('link');
        $campaignExists = $campaign->whereLink($link)->where('start_date', '<=', $now)->where('end_date', '>=', $now)->firstOrFail();

        $toReturn['success'] = 1;
        $toReturn['customFields'] = json_decode($campaignExists->custom_fields);

        return json_encode($toReturn);
    }

    public function recruitUser(RecrutementCampaign $campaign, RecrutedUser $usr, User $userObj, SignupRequest $req) {
        $now = date('Y-m-d');
        $link = Input::get('link');
        $campaignExists = $campaign->whereLink($link)->where('start_date', '<=', $now)->where('end_date', '>=', $now)->firstOrFail();

        $email = Input::get('contact_email');
        $emailHash = $userObj->getEmailHash($email);

        if($usr->checkEmailHash($emailHash, 0)) {
            $toReturn['success'] = 0;
            $toReturn['message'] = "Email already exists!";
            return response(json_encode($toReturn), 200);
        }

        $usr->campaign_id = $campaignExists->id;
        $usr->first_name = Input::get('first_name');
        $usr->last_name = Input::get('last_name');
        $usr->date_of_birth = Input::get('date_of_birth');
        $usr->gender = Input::get('gender');
        $usr->university = Input::get('university');
        $usr->studies_type_id = Input::get('studies_type');
        $usr->studies_field_id = Input::get('study_field');
        $usr->email = $email;
        $usr->phone = Input::get('phone');
        $usr->address = Input::get('address');
        $usr->city = Input::get('city');
        $usr->zipcode = Input::get('zipcode');
        $usr->email_hash = $emailHash;

        $responses = Input::get('fields');
        $allFields = json_decode($campaignExists->custom_fields);
        if(count($allFields) != count($responses)) {
            $toReturn['success'] = 0;
            $toReturn['message'] = "Please respond to all questions!";
            return response(json_encode($toReturn), 200);
        }

        $usr->custom_responses = json_encode($responses);
        $usr->save();

        $toReturn = array(
            'success'   =>  1,
        );

        return response(json_encode($toReturn), 200);
    }

    public function getRecrutedUsers(RecrutedUser $user, Request $req) {
        $userData = $req->get('userData');

        $search = array(
            'name'                  =>  Input::get('name'),
            'email'                 =>  Input::get('contact_email'),
            'antenna_id'            =>  Input::get('antenna_id'),
            'campaign_id'           =>  Input::get('campaign'),
            'status'                =>  Input::get('status'),
            'sidx'                  =>  Input::get('sidx'),
            'sord'                  =>  Input::get('sord'),
            'limit'                 =>  empty(Input::get('rows')) ? 10 : Input::get('rows'),
            'page'                  =>  empty(Input::get('page')) ? 1 : Input::get('page')
        );

        if($userData['is_superadmin'] != 1) {
            $search['antenna_id'] = $userData['antenna_id'];
        }

        $users = $user->getFiltered($search);
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

        foreach($users as $usr) {
            $actions = "";
            if($isGrid) {
                $actions .= "<button class='btn btn-default btn-xs clickMe' title='View details' ng-click='vm.getUserDetails(".$usr->id.", true)'><i class='fa fa-search'></i></button>";
            } else {
                $actions = $usr->id;
            }

            $toReturn['rows'][] = array(
                'id'    =>  $usr->id,
                'cell'  =>  array(
                    $actions,
                    $usr->first_name." ".$usr->last_name,
                    $usr->date_of_birth->format('d/m/Y'),
                    $usr->email,
                    $usr->getGenderText(),
                    $usr->antenna_name,
                    $usr->getStatus(true)
                )
            );
        }

        return response(json_encode($toReturn), 200);
    }

    public function getUserDetails(RecrutedUser $user, RecrutedComment $comm) {
        $id = Input::get('id');

        $user = $user->with('studyType')->with('studyField')->with('recrutement_campaigns')->findOrFail($id);

        $comments = array();
        $comm = $comm->with('user')->where('recruted_user_id', $user->id)->orderBy('created_at', 'ASC')->get();
        foreach ($comm as $comment) {
            $comments[] = array(
                'comment'           =>  $comment->comment,
                'created_at'        =>  $comment->created_at->format('d/m/Y H:i:s'),
                'user_id'           =>  $comment->user_id,
                'commenter_name'    =>  $comment->user->first_name." ".$comment->user->last_name
            );
        }

        $toReturn = array(
            'id'                => $user->id,
            'first_name'        => $user->first_name,
            'last_name'         => $user->last_name,
            'date_of_birth'     => $user->date_of_birth->format('d/m/Y'),
            'gender'            => $user->getGenderText(),
            'email'             => $user->email,
            'phone'             => $user->phone,
            'city'              => $user->city,
            'address'           => $user->address,
            'zipcode'           => $user->zipcode,
            'university'        => $user->university,
            'study_type'        => $user->studyType->name,
            'study_field'       => $user->studyField->name,
            'status'            => $user->getStatus(true),
            'custom_responses'  => json_decode($user->custom_responses),
            'comments'          => $comments,
            'status_transitions'=> $user->getWorkflowTransitions()
        );
        return response(json_encode($toReturn), 200);
    }

    public function addComment(RecrutedComment $comm, Request $req) {
        $userData = $req->get('userData');

        $comm->user_id = $userData['id'];
        $comm->comment = Input::get('comment');
        $comm->recruted_user_id = Input::get('user_id');
        $comm->save();

        $toReturn['success'] = 1;
        return response(json_encode($toReturn), 200);
    }

    public function changeStatus(RecrutedUser $user) {
        $id = Input::get('user_id');
        $newStatus = Input::get('newStatus');
        $user = $user->findOrFail($id);
        $status_transitions = $user->getWorkflowTransitions();

        if(!in_array($newStatus, array_keys($status_transitions))) {
            $toReturn['success'] = 1;
            $toReturn['message'] = "Cannot transition to this status! Please try again!";
            return response(json_encode($toReturn), 422);
        }

        $user->status = $newStatus;
        $user->save();

        $toReturn['success'] = 1;
        return response(json_encode($toReturn), 200);
    }

    public function activateUserRecruted(RecrutedUser $rUser, User $user, Role $role, Fee $fee, EmailTemplate $tpl) {
        // User..
        $id = Input::get('id');
        $rUser = $rUser->with('recrutement_campaigns')->findOrFail($id);

        // Creating new user..
        $user->contact_email = $rUser->email;
        $user->first_name = $rUser->first_name;
        $user->last_name = $rUser->last_name;
        $user->date_of_birth = $rUser->date_of_birth;
        $user->gender = $rUser->gender;
        $user->antenna_id = $rUser->recrutement_campaigns->antenna_id;
        $user->university = $rUser->university;
        $user->studies_type_id = $rUser->studies_type_id;
        $user->studies_field_id = $rUser->studies_field_id;
        $user->phone = $rUser->phone;
        $user->address = $rUser->address;
        $user->city = $rUser->city;
        $user->zipcode = $rUser->zipcode;

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
                $this->getOauthCredentials(),
                $domain,
                $user->seo_url,
                $userPass
            );

            $user->internal_email = $username;
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
            $tmpRole = new UserRole();
            $tmpRole->user_id = $user->id;
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

        // Marking signup as accepted..
        $rUser->status = 2;
        $rUser->user_id_created = $user->id;
        $rUser->save();

        $toReturn['success'] = 1;
        return response(json_encode($toReturn), 200);
    }
}
