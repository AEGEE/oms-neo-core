<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = "addresses";

    // Relationships..
    public function country() {
    	return $this->belongsTo('App\Models\Country');
    }

    public function universities() {
    	return $this->hasMany('App\Models\Universities');
    }
    // Model methods go down here..
}
