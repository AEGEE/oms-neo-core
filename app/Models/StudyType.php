<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyType extends Model
{
    protected $table = "study_types";

    // Relationships..
    public function user() {
    	return $this->hasMany('App\Models\User');
    }

    // Model methods go down here..
}
