<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auth extends Model
{
    protected $table = "auths";

    // Relationships..
    public function user() {
    	return $this->belongsTo('App\Models\User');
    }

    // Model methods go down here..
}
