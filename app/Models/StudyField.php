<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyField extends Model
{
    protected $table = "study_fields";

    // Relationships..
	public function recrutedMember() {
        return $this->hasMany('App\Models\RecruitedMember');
    }

    public function member() {
    	return $this->hasMany('App\Models\Member');
    }

    // Model methods go down here..
}
