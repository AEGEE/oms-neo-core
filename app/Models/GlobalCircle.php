<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlobalCircle extends Model
{
    protected $table = "global_circles";

    // Relationships..
    public function bodyCircles() {
    	return $this->hasMany('App\Models\BodyCircle', 'global_circle_id');
    }

    public function bodies() {
    	return $this->belongsToMany('App\Models\Body', 'body_circles', 'global_circle_id', 'body_id');
    }
}
