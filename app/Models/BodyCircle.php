<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Util;

class BodyCircle extends Model
{
    protected $table = "body_circles";

    public function setNameAttribute($value) {
        $this->attributes['name_slug'] = Util::slugify($value);
        $this->attributes['name'] = $value;
    }

    // Relationships..
    public function body() {
    	return $this->belongsTo('App\Models\Body');
    }

    public function memberships() {
    	return $this->belongsToMany('App\Models\MembershipCircle', 'body_membership_circles', 'circle_id', 'membership_id');
    }

    public function globalCircle() {
        return $this->belongsTo('App\Models\GlobalCircle')
    }

    // Model methods go down here..
}
