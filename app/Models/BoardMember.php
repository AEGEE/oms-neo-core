<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoardMember extends Model
{
    protected $table = "board_members";

    // Relationships..
    public function user() {
    	return $this->belongsTo('App\Models\User');
    }

    public function workingGroup() {
    	return $this->belongsTo('App\Models\WorkingGroup');
    }

    // Model methods go down here..
}
