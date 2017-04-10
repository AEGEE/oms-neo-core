<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberBodyRelation extends Model
{
    protected $table = "member_body_relations";

    // Relationships..
    public function member() {
    	return $this->belongsTo('App\Models\Member');
    }

    public function body() {
    	return $this->belongsTo('App\Models\Body');
    }
}
