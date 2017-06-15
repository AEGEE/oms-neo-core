<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class BodyCircle extends Model
{
    protected $table = "body_circles";
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

    public function globalCircle() {
        return $this->belongsTo('App\Models\GlobalCircle');
    }
}
