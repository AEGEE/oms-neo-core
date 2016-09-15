<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecrutedUser extends Model
{
    protected $table = "recruted_users";

    // Relationships..
    public function recruted_comment() {
        return $this->hasMany('App\Models\RecrutedComment');
    }

    public function recrutement_campaigns() {
        return $this->belongsTo('App\Models\RecrutementCampaign');
    }

    public function user() {
    	return $this->belongsTo('App\Models\User', 'user_id_created');
    }

    // Model methods go down here..
    public function checkEmailHash($emailHash, $exceptId) {
        return $this->where('email_hash', $emailHash)->where('id', '!=', $exceptId)->count() >= 1;
    }
}
