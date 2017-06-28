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
    	return $this->belongsToMany('App\Models\BodyMembership', 'body_membership_circles', 'circle_id', 'membership_id');
    }

    public function getUsers() {
    	return $this->memberships()->get()->map(function ($membership) {
            return $membership->user;
        });
    }

    public function parentCircle() {
        return $this->belongsTo('App\Models\Circle');
    }

    public function childrenCircles() {
        return $this->hasMany('App\Models\Circle');
    }
}
