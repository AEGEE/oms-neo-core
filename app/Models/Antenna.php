<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Antenna extends Model
{
    protected $table = "antennas";

    // Relationships..
    public function country() {
    	return $this->belongsTo('App\Models\Country');
    }

    public function user() {
    	return $this->hasMany('App\Models\User');
    }

    // Model methods go down here..
}
