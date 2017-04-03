<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserWorkingGroup extends Model
{
    protected $table = "user_working_groups";

    protected $fillable = ['user_id', 'work_group_id'];

    // Relationships..
    public function user() {
    	return $this->belongsTo('App\Models\Member');
    }

    public function workingGroup() {
    	return $this->belongsTo('App\Models\WorkingGroup');
    }

    // Model methods go down here..
}
