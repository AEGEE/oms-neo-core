<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    protected $table = "fees";

    // Relationships..
    public function feeUser() {
    	return $this->hasMany('App\Models\FeeUser');
    }

    public function users() {
        return $this->belongsToMany('App\Models\User', 'fee_users', 'fee_id', 'user_id')
                    ->withPivot('date_paid', 'expiration_date');
    }

    // Model methods go down here..
}
