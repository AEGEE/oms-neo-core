<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeUser extends Model
{
    protected $table = "fee_users";

    // Relationships..
    public function user() {
    	return $this->belongsTo('App\Models\User');
    }

    public function fee() {
    	return $this->belongsTo('App\Models\Fee');
    }

    // Model methods go down here..
    public function getPaidUserFees($userId, $date) {
        $feesPaid = $this->where('user_id', $userId)
        					->where('date_paid', '<=', $date)
        					->where(function($query) use ($date) {
        							$query->where('expiration_date', '>=', $date)
        									->orWhereNull('expiration_date');
        						})
        					->get();
        $toReturn = array();
        foreach($feesPaid as $fee) {
        	$toReturn[] = $fee->fee_id;
        }

        return $toReturn;
    }

}
