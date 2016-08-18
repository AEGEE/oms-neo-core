<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = "countries";

    // Relationships..
    public function antenna() {
    	return $this->hasMany('App\Models\Antenna');
    }

    // Model methods go down here..
}
