<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BodyMembershipCircle extends Model
{
    protected $table = "body_membership_circles";
    protected $guarded = ["id"];

    // Relationships..
    public function membership() {
    	return $this->belongsTo('App\Models\BodyMembershipCircle');
    }

    public function circle() {
    	return $this->belongsTo('App\Models\BodyCircle');
    }

    public function position() {
        return $this->belongsTo('App\Models\Position');
    }
}
