<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Circle extends Model
{
    protected $table = "circles";
    protected $guarded = ["id"];

    // Relationships..
    public function body() {
    	return $this->belongsTo('App\Models\Body');
    }

    public function memberships() {
    	return $this->hasMany('App\Models\CircleMembership');
    }

    public function users() {
        return $this->belongsToMany('App\Models\User', 'circle_memberships', 'circle_id', 'user_id');
    }

    public function parentCircle() {
        return $this->belongsTo('App\Models\Circle');
    }

    public function childrenCircles() {
        return $this->hasMany('App\Models\Circle');
    }


    public function getChildrenRecursive() {
        return collect([$this])->merge($this->childrenCircles->flatMap(function ($circle) { return $circle->getChildrenRecursive(); }));
    }
}
