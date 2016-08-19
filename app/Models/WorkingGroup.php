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

    public function users() {
        return $this->belongsToMany('App\Models\User', 'user_working_groups', 'work_group_id', 'user_id')
                    ->withPivot('start_date', 'end_date');
    }

    // Model methods go down here..
}
