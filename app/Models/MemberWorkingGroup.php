<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberWorkingGroup extends Model
{
    protected $table = "member_working_groups";

    protected $fillable = ['member_id', 'work_group_id'];

    // Relationships..
    public function member() {
    	return $this->belongsTo('App\Models\Member');
    }

    public function workingGroup() {
    	return $this->belongsTo('App\Models\WorkingGroup');
    }

    // Model methods go down here..
}
