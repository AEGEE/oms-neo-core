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

    public function roleModulePage() {
    	return $this->hasMany('App\Models\RoleModulePage');
    }

    // Model methods go down here..
}
