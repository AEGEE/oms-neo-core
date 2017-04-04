<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeMember extends Model
{
    protected $table = "fee_members";

    // Relationships..
    public function member() {
    	return $this->belongsTo('App\Models\Member');
    }

    public function fee() {
    	return $this->belongsTo('App\Models\Fee');
    }

    // Model methods go down here..
    public function getPaidMemberFees($memberId, $date) {
        $feesPaid = $this->where('member_id', $memberId)
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
