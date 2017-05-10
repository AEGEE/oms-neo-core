<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BodyType extends Model
{
    protected $table = "body_types";

    // Relationships..
    public function body() {
    	return $this->hasMany('App\Models\Body');
    }
}
