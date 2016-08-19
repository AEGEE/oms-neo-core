<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModulePage extends Model
{
    protected $table = "module_pages";

    // Relationships..
    public function module() {
    	return $this->belongsTo('App\Models\Module');
    }

    public function roles() {
        return $this->belongsToMany('App\Models\Role', 'role_module_pages', 'module_page_id', 'role_id')
                    ->withPivot('permission_level');
    }

    public function roleModulePage() {
    	return $this->hasMany('App\Models\RoleModulePage');
    }

    // Model methods go down here..
}
