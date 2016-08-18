<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleModulePage extends Model
{
    protected $table = "role_module_pages";

    // Relationships..
    public function modulePage() {
    	return $this->belongsTo('App\Models\ModulePage');
    }

    public function role() {
    	return $this->belongsTo('App\Models\Role');
    }

    // Model methods go down here..
}
