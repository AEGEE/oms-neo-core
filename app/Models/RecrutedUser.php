<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use DB;

class RecrutedUser extends Model
{
    protected $table = "recruted_users";

     protected $dates = ['created_at', 'updated_at', 'date_of_birth'];

    // Relationships..
    public function recruted_comment() {
        return $this->hasMany('App\Models\RecrutedComment');
    }

    public function recrutement_campaigns() {
        return $this->belongsTo('App\Models\RecrutementCampaign');
    }

    public function user() {
    	return $this->belongsTo('App\Models\User', 'user_id_created');
    }

    // Model methods go down here..
    public function checkEmailHash($emailHash, $exceptId) {
        return $this->where('email_hash', $emailHash)->where('id', '!=', $exceptId)->count() >= 1;
    }

    public function getStatus($isGrid = false) {
        switch ($this->status) {
            case '1':
                $labelType = "warning";
                $statusText = "In progress";
                break;
            case '-1':  
                $labelType = "danger";
                $statusText = "Rejected";
                break;
            case '2':
                $labelType = "success";
                $statusText = "Accepted";
                break;
            default:
                $labelType = "primary";
                $statusText = "Opened";
                break;
        }
        if($isGrid) {
            $statusText = "<span class='label label-".$labelType."'>".$statusText."</span>";
        }
        return $statusText;
    }

    public function getGenderText() {
        $genderText = "";

        switch ($this->gender) {
            case '1':
                $genderText = "Male";
                break;
            case '2':
                $genderText = "Female";
                break;
            case '3':
                $genderText = "Other";
                break;
        }
        return $genderText;
    }

    public function getFiltered($search = array(), $onlyTotal = false) {
        $user = $this->select('antennas.name as antenna_name', 'recruted_users.*')
                    ->join('recrutement_campaigns', 'recrutement_campaigns.id', '=', 'recruted_users.campaign_id')
                    ->join('antennas', 'antennas.id', '=', 'recrutement_campaigns.antenna_id');

        // Filters here..
        if(isset($search['name']) && !empty($search['name'])) {
            $user = $user->where(DB::raw('CONCAT (first_name, \' \', last_name)'), 'LIKE', '%'.$search['name'].'%');
        }

        if(isset($search['email']) && !empty($search['email'])) {
            $user = $user->where('email', $search['email']);
        }

        if(isset($search['antenna_id']) && !empty($search['antenna_id'])) {
            $user = $user->where('antenna_id', $search['antenna_id']);
        }

        if(isset($search['status']) && !empty($search['status'])) {
            $search['status'] = ($search['status'] == 0) ? null : $search['status'];
            $user = $user->where('status', $search['status']);
        }

        if(isset($search['campaign_id']) && !empty($search['campaign_id'])) {
            $user = $user->where('campaign_id', $search['campaign_id']);
        }
        // END filters..

        if($onlyTotal) {
            return $user->count();
        }

        // Ordering..
        $sOrder = (isset($search['sord']) && ($search['sord'] == 'asc' || $search['sord'] == 'desc')) ? $search['sord'] : 'asc';
        if(isset($search['sidx'])) {
            switch ($search['sidx']) {
                case 'full_name':
                    $user = $user->orderBy('first_name', $search['sord'])->orderBy('last_name', $search['sord']);
                    break;

                default:
                    $user = $user->orderBy('first_name', $search['sord'])->orderBy('last_name', $search['sord']);
                    break;
            }
        }

        if(!isset($search['noLimit']) || !$search['noLimit']) {
            $limit  = !isset($search['limit']) || empty($search['limit']) ? 10 : $search['limit'];
            $page   = !isset($search['page']) || empty($search['page']) ? 1 : $search['page'];
            $from   = ($page - 1)*$limit;
            $user = $user->take($limit)->skip($from);
        }

        return $user->get();
    }
}
