<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    protected $table = "fees";

    // Relationships..
    public function feeUser() {
    	return $this->hasMany('App\Models\FeeUser');
    }

    // Model methods go down here..
}
