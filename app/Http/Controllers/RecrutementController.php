<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\AddRecrutmentCampaignRequest;
use App\Http\Requests\SignupRequest;

use App\Models\RecrutedUser;
use App\Models\RecrutementCampaign;
use App\Models\User;

use Input;

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
    	$campaign->link = Input::get('link');

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
                // $actions .= "<button class='btn btn-default btn-xs clickMeFee' title='Edit' ng-click='vm.editFee(".$feeX->id.")'><i class='fa fa-pencil'></i></button>";
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
}
