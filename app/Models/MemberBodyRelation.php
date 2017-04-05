<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberBodyRelation extends Model
{
    protected $table = "member_body_relations";

    // Relationships..
    public function member() {
    	return $this->hasOne('App\Models\Member');
    }

    public function body() {
    	return $this->hasOne('App\Models\Body');
    }
}
