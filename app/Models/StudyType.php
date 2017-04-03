<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyType extends Model
{
    protected $table = "study_types";

    // Relationships..
    public function recrutedUser() {
        return $this->hasMany('App\Models\RecrutedUser');
    }
    
    public function user() {
    	return $this->hasMany('App\Models\Member');
    }

    // Model methods go down here..
}
