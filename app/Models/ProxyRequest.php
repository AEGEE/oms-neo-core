<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProxyRequest extends Model
{
    protected $table = "proxy_requests";

    // Relationships..
    public function moduleFrom() {
    	return $this->belongsTo('App\Models\Module', 'from_module_id');
    }

    public function moduleTo() {
    	return $this->belongsTo('App\Models\Module', 'to_module_id');
    }

    // Model methods go down here..
}
