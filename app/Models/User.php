<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use DB;

class User extends Model
{
    protected $table = "users";

    protected $dates = ['created_at', 'updated_at', 'date_of_birth', 'activated_at'];

    // Relationships..
    public function antenna() {
    	return $this->belongsTo('App\Models\Antenna');
    }

    public function auth() {
    	return $this->hasMany('App\Models\Auth');
    }

    public function boardMember() {
    	return $this->hasMany('App\Models\BoardMember');
    }

    public function department() {
    	return $this->belongsTo('App\Models\Department');
    }

    public function fees() {
        return $this->belongsToMany('App\Models\Fee', 'fee_users', 'user_id', 'fee_id')
                    ->withPivot('date_paid', 'expiration_date');
    }

    public function feeUser() {
    	return $this->hasMany('App\Models\FeeUser');
    }

    public function roles() {
        return $this->belongsToMany('App\Models\Roles', 'user_roles', 'user_id', 'role_id');
    }

    public function studyField() {
    	return $this->belongsTo('App\Models\StudyField', 'studies_field_id');
    }

    public function studyType() {
    	return $this->belongsTo('App\Models\StudyType', 'studies_type_id');
    }

    public function userRole() {
    	return $this->hasMany('App\Models\UserRole');
    }

    public function userWorkingGroup() {
    	return $this->hasMany('App\Models\UserWorkingGroup');
    }

    public function workingGroups() {
        return $this->belongsToMany('App\Models\WorkingGroup', 'user_working_groups', 'user_id', 'work_group_id')
                    ->withPivot('start_date', 'end_date');
    }

    // Accessors..
    public function getGenderAttribute($gender) {
        $genderText = "";

        switch ($gender) {
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

    public function getInternalEmailAttribute($value) {
        return empty($value) ? "No internal email assigned!" : $value;
    }

    public function getStatusTextAttribute($value) {
        $suspended = empty($this->is_suspended) ? false : true;
        $active = empty($this->activated_at) ? false : true;
        $status = "Active";
        if($suspended) {
            $status = "Suspended";
        } elseif(!$active) {
            $status = "Inactive";
        }

        return $status;
    }

    public function getStatusAttribute($value) {
        $suspended = empty($this->is_suspended) ? false : true;
        $active = empty($this->activated_at) ? false : true;
        $status = "1";
        if($suspended) {
            $status = "3";
        } elseif(!$active) {
            $status = "2";
        }

        return $status;
    }

    // Model methods go down here..
    public function getEmailHash($email) {
        $emailHash = strtolower($email);

        $emailSplit = explode('@', $emailHash);
        $emailUsername = $emailSplit[0];
        $emailTld = $emailSplit[1];

        // We remove any labels we have in the email name (everything that's after +)
        $emailUsername = preg_replace('/\+.*/', "", $emailUsername);

        // Special check for gmail accounts..
        if(preg_match('/gmail/', $emailTld)) {
            // If it's gmail, then dots (.) don't represent anything in the email username, so we just remove them..
            $emailUsername = preg_replace('/\./', '', $emailUsername);
        }

        $emailHash = $emailUsername."@".$emailTld;
        return $emailHash;
    }

    public function checkEmailHash($emailHash) {
        return $this->where('email_hash', $emailHash)->count() >= 1;
    }

    public function getFiltered($search = array(), $onlyTotal = false) {
        $users = $this
                        ->with('antenna')
                        ->with('department')
                        ->with('studyField')
                        ->with('StudyType');

        // Filters here..
        if(isset($search['name']) && !empty($search['name'])) {
            $users = $users->where(DB::raw('CONCAT (first_name, \' \', last_name)'), 'LIKE', '%'.$search['name'].'%');
        }

        if(isset($search['date_of_birth']) && !empty($search['date_of_birth'])) {
            $users = $users->where('date_of_birth', $search['date_of_birth']);
        }

        if(isset($search['contact_email']) && !empty($search['contact_email'])) {
            $users = $users->where('contact_email', $search['contact_email']);
        }

        if(isset($search['gender']) && !empty($search['gender'])) {
            $users = $users->where('gender', $search['gender']);
        }

        if(isset($search['antenna_id']) && !empty($search['antenna_id'])) {
            $users = $users->where('antenna_id', $search['antenna_id']);
        }

        if(isset($search['department_id']) && !empty($search['department_id'])) {
            $users = $users->where('department_id', $search['department_id']);
        }

        if(isset($search['internal_email']) && !empty($search['internal_email'])) {
            $users = $users->where('internal_email', $search['internal_email']);
        }

        if(isset($search['studies_type_id']) && !empty($search['studies_type_id'])) {
            $users = $users->where('studies_type_id', $search['studies_type_id']);
        }

        if(isset($search['studies_field_id']) && !empty($search['studies_field_id'])) {
            $users = $users->where('studies_field_id', $search['studies_field_id']);
        }

        if(isset($search['status']) && !empty($search['status'])) {
            switch ($search['status']) {
                case '1':
                    $users = $users->whereNull('is_suspended')->whereNotNull('activated_at');
                    break;
                case '2':
                    $users = $users->whereNull('activated_at');
                    break;
                case '3':
                    $users = $users->whereNotNull('is_suspended');
                    break;
            }
        }
        // END filters..

        if($onlyTotal) {
            return $users->count();
        }

        // Ordering..
        $sOrder = (isset($search['sord']) && ($search['sord'] == 'asc' || $search['sord'] == 'desc')) ? $search['sord'] : 'asc';
        if(isset($search['sidx'])) {
            switch ($search['sidx']) {
                case 'name':
                    $users = $users->orderBy('first_name', $search['sord'])->orderBy('last_name', $search['sord']);
                    break;
                case 'date_of_birth':
                case 'contact_email':
                case 'gender':
                case 'internal_email':
                    $users = $users->orderBy($search['sidx'], $search['sord']);
                    break;

                default:
                    $users = $users->orderBy('first_name', $search['sord'])->orderBy('last_name', $search['sord']);
                    break;
            }
        }

        if(!isset($search['noLimit']) || !$search['noLimit']) {
            $limit  = !isset($search['limit']) || empty($search['limit']) ? 10 : $search['limit'];
            $page   = !isset($search['page']) || empty($search['page']) ? 1 : $search['page'];
            $from   = ($page - 1)*$limit;
            $users = $users->take($limit)->skip($from);
        }

        return $users->get();
    }
}
