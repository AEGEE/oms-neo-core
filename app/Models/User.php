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

    public function feeUser() {
    	return $this->hasMany('App\Models\FeeUser');
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

    // Model methods go down here..
}
