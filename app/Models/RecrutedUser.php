<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use DB;

class RecruitedMember extends Model
{
    protected $table = "recruted_members";

    protected $dates = ['created_at', 'updated_at', 'date_of_birth'];

    /**
     * Workflow transitions
     * 0 / null = Opened
     * 1 = In progress
     * 2 = Accepted
     * -1 = Rejected
     */
    protected $workflowTransitions = array(
        '-1'    =>  array(), // rejected is final
        '0'     =>  array(-1, 1, 2), // opened can go to any
        '1'     =>  array(-1, 2), // in progress can only accept or reject
        '2'     =>  array() // accepted is final
    );

    // Relationships..
    public function recruted_comment() {
        return $this->hasMany('App\Models\RecruitedComment');
    }

    public function recrutement_campaigns() {
        return $this->belongsTo('App\Models\RecruitementCampaign', 'campaign_id');
    }

    public function studyField() {
        return $this->belongsTo('App\Models\StudyField', 'studies_field_id');
    }

    public function studyType() {
        return $this->belongsTo('App\Models\StudyType', 'studies_type_id');
    }

    public function member() {
    	return $this->belongsTo('App\Models\Member', 'member_id_created');
    }

    // Model methods go down here..
    public function getWorkflowTransitions($needsMarkup = false) {
        $currentPlace = empty($this->status) ? 0 : $this->status; // In case we send null..
        $available_transitions = $this->workflowTransitions[$currentPlace];

        $toReturn = array();
        foreach ($available_transitions as $value) {
            $toReturn[$value] = $this->getStatusStuff($value);
        }

        return $toReturn;
    }

    public function checkEmailHash($emailHash, $exceptId) {
        return $this->where('email_hash', $emailHash)->where('id', '!=', $exceptId)->count() >= 1;
    }

    private function getStatusStuff($status) {
        $toReturn = array();
        switch ($status) {
            case '1':
                $toReturn['labelType'] = "warning";
                $toReturn['statusText'] = "In progress";
                break;
            case '-1':  
                $toReturn['labelType'] = "danger";
                $toReturn['statusText'] = "Rejected";
                break;
            case '2':
                $toReturn['labelType'] = "success";
                $toReturn['statusText'] = "Accepted";
                break;
            default:
                $toReturn['labelType'] = "primary";
                $toReturn['statusText'] = "Opened";
                break;
        }

        return $toReturn;
    }

    public function getStatus($isGrid = false) {
        $statusDetails = $this->getStatusStuff($this->status);
        if($isGrid) {
            $statusText = "<span class='label label-".$statusDetails['labelType']."'>".$statusDetails['statusText']."</span>";
        } else {
            $statusText = $statusDetails['statusText'];
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
        $member = $this->select('antennas.name as antenna_name', 'recruted_members.*')
                    ->join('recrutement_campaigns', 'recrutement_campaigns.id', '=', 'recruted_members.campaign_id')
                    ->join('antennas', 'antennas.id', '=', 'recrutement_campaigns.antenna_id');

        // Filters here..
        if(isset($search['name']) && !empty($search['name'])) {
            $member = $member->where(DB::raw('CONCAT (first_name, \' \', last_name)'), 'LIKE', '%'.$search['name'].'%');
        }

        if(isset($search['email']) && !empty($search['email'])) {
            $member = $member->where('email', $search['email']);
        }

        if(isset($search['antenna_id']) && !empty($search['antenna_id'])) {
            $member = $member->where('antenna_id', $search['antenna_id']);
        }

        if(isset($search['status'])) {
            if($search['status'] == 0) {
                $member = $member->whereNull('status');
            } else {
                $member = $member->where('status', $search['status']);
            }
        }

        if(isset($search['campaign_id']) && !empty($search['campaign_id'])) {
            $member = $member->where('campaign_id', $search['campaign_id']);
        }
        // END filters..

        if($onlyTotal) {
            return $member->count();
        }

        // Ordering..
        $sOrder = (isset($search['sord']) && ($search['sord'] == 'asc' || $search['sord'] == 'desc')) ? $search['sord'] : 'asc';
        if(isset($search['sidx'])) {
            switch ($search['sidx']) {
                case 'full_name':
                    $member = $member->orderBy('first_name', $search['sord'])->orderBy('last_name', $search['sord']);
                case 'created_at':
                    $member = $member->orderBy('created_at', $search['sord']);
                    break;

                default:
                    $member = $member->orderBy('first_name', $search['sord'])->orderBy('last_name', $search['sord']);
                    break;
            }
        }

        if(!isset($search['noLimit']) || !$search['noLimit']) {
            $limit  = !isset($search['limit']) || empty($search['limit']) ? 10 : $search['limit'];
            $page   = !isset($search['page']) || empty($search['page']) ? 1 : $search['page'];
            $from   = ($page - 1)*$limit;
            $member = $member->take($limit)->skip($from);
        }

        return $member->get();
    }
}
