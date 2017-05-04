<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipCircle extends Model
{
    protected $table = "body_membership_circles";

    // Relationships..
    public function BodyMembership() {
    	return $this->belongsTo('App\Models\BodyMembership');
    }

    public function circle() {
    	return $this->belongsTo('App\Models\BodyCircle');
    }

    // Model methods go down here..
}
