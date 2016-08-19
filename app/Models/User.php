<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = "users";

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
    	return $this->belongsTo('App\Models\StudyField');
    }

    public function studyType() {
    	return $this->belongsTo('App\Models\StudyType');
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

    // Model methods go down here..
}
