<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = "departments";

    // Relationships..
    public function user() {
    	return $this->hasMany('App\Models\User');
    }

    public function boardMembers() {
    	return $this->hasMany('App\Models\BoardMember');
    }

    // Model methods go down here..
}
