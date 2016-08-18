<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkingGroup extends Model
{
    protected $table = "working_groups";

    // Relationships..
    public function userWorkingGroup() {
    	return $this->hasMany('App\Models\UserWorkingGroup');
    }

    // Model methods go down here..
}
