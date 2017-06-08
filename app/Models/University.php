<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class University extends Model
{
    protected $table = "universities";

    // Relationships..
    public function address() {
    	return $this->belongsTo('App\Models\Address');
    }

    public function studies() {
    	return $this->hasMany('App\Models\Study');
    }

    public function users() {
        return $this->belongsToMany('App\Models\User', 'studies', 'university_id', 'user_id');
    }
}
