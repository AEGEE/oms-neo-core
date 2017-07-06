<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CircleMembership extends Model
{
    protected $table = "circle_memberships";
    protected $guarded = ["id"];

    // Relationships..
    public function user() {
    	return $this->belongsTo('App\Models\User');
    }

    public function circle() {
    	return $this->belongsTo('App\Models\BodyCircle');
    }

    public function position() {
        return $this->belongsTo('App\Models\Position');
    }
}
