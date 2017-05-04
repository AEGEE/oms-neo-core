<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Study extends Model
{
    protected $table = "studies";

    // Relationships..
    public function studyField() {
    	return $this->belongsTo('App\Models\StudyField');
    }

    public function studyType() {
    	return $this->hasMany('App\Models\StudyType');
    }

    public function university() {
        return $this->belongsTo('App\Models\University');
    }

    public function user() {
        return $this->belongsTo('App\Models\User');
    }
}
