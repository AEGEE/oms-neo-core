<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyType extends Model
{
    protected $table = "study_types";

    // Relationships..
    public function recrutedMember() {
        return $this->hasMany('App\Models\RecrutedMember');
    }
    
    public function member() {
    	return $this->hasMany('App\Models\Member');
    }

    // Model methods go down here..
}
