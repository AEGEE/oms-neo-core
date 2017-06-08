<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BodyMembership extends Model
{
    protected $table = "body_memberships";
    protected $guarded = ["id"];

    // Relationships..
    public function user() {
    	return $this->belongsTo('App\Models\User');
    }

    public function body() {
    	return $this->belongsTo('App\Models\Body');
    }

    public function bodyCircles() {
        return $this->belongsToMany('App\Model\BodyCircle', 'body_membership_circles', 'membership_id', 'circle_id');
    }

    // Model methods go down here..
}
