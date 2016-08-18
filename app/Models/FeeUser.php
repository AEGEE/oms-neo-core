<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeUser extends Model
{
    protected $table = "fee_users";

    // Relationships..
    public function user() {
    	return $this->belongsTo('App\Models\User');
    }

    public function fee() {
    	return $this->belongsTo('App\Models\Fee');
    }

    // Model methods go down here..
}
