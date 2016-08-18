<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = "roles";

    // Relationships..
    public function roleModulePages() {
    	return $this->hasMany('App\Models\RoleModulePage');
    }

    public function userRole() {
    	return $this->hasMany('App\Models\UserRole');
    }

    // Model methods go down here..
}
