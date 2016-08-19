<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = "roles";

    // Relationships..
    public function modulePages() {
        return $this->belongsToMany('App\Models\ModulePage', 'role_module_pages', 'role_id', 'module_page_id')
                    ->withPivot('permission_level');
    }

    public function roles() {
        return $this->belongsToMany('App\Models\User', 'user_roles', 'role_id', 'user_id');
    }

    public function roleModulePages() {
    	return $this->hasMany('App\Models\RoleModulePage');
    }

    public function userRole() {
    	return $this->hasMany('App\Models\UserRole');
    }

    // Model methods go down here..
}
