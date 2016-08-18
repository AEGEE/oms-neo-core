<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserWorkingGroup extends Model
{
    protected $table = "user_working_groups";

    // Relationships..
    public function user() {
    	return $this->belongsTo('App\Models\User');
    }

    public function workingGroup() {
    	return $this->belongsTo('App\Models\WorkingGroup');
    }

    // Model methods go down here..
}
