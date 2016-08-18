<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $table = "modules";

    // Relationships..
    public function proxyRequestFrom() {
    	return $this->hasMany('App\Models\ProxyRequest', 'from_module_id');
    }

    public function proxyRequestTo() {
    	return $this->hasMany('App\Models\ProxyRequest', 'to_module_id');
    }

    public function modulePage() {
    	return $this->hasMany('App\Models\ModulePage');
    }

    // Model methods go down here..
}
