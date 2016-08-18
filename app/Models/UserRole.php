<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $table = "user_roles";

    // Relationships..
    public function user() {
    	return $this->belongsTo('App\Models\User');
    }

    public function role() {
    	return $this->belongsTo('App\Models\Role');
    }

    // Model methods go down here..
}
