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

    // Model methods go down here..
}
