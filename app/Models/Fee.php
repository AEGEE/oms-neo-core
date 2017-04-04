<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class Fee extends Model
{
    protected $table = "fees";

    protected $dates = ['created_at', 'updated_at', 'date_paid', 'expiration_date'];

    // Relationships..
    public function FeeMember() {
    	return $this->hasMany('App\Models\FeeMember');
    }

    public function members() {
        return $this->belongsToMany('App\Models\Member', 'fee_members', 'fee_id', 'member_id')
                    ->withPivot('date_paid', 'expiration_date');
    }

    // Model methods go down here..
    public function getFiltered($search = array(), $onlyTotal = false) {
        $fees = $this;

        // Filters here..
        if(isset($search['name']) && !empty($search['name'])) {
            $fees = $fees->where('name', 'LIKE', "%".$search['name']."%");
        }

        if(isset($search['availability_from']) && !empty($search['availability_from'])) {
            $fees = $fees->where('availability', '>=', $search['availability_from']);
        }

        if(isset($search['availability_to']) && !empty($search['availability_to'])) {
            $fees = $fees->where('availability', '<=', $search['availability_to']);
        }

        if(isset($search['availability_unit']) && !empty($search['availability_unit'])) {
            $fees = $fees->where('availability_unit', $search['availability_unit']);
        }

        if(isset($search['price_from']) && !empty($search['price_from'])) {
            $fees = $fees->where('price', '>=', $search['price_from']);
        }

        if(isset($search['price_to']) && !empty($search['price_to'])) {
            $fees = $fees->where('price', '<=', $search['price_to']);
        }

        if(isset($search['currency']) && !empty($search['currency'])) {
            $fees = $fees->where('currency', $search['currency']);
        }

        if(isset($search['mandatory']) && !empty($search['mandatory'])) {
            switch ($search['mandatory']) {
                case '1':
                    $fees = $fees->whereNotNull('is_mandatory');
                    break;
                case '2':
                    $fees = $fees->whereNull('is_mandatory');
                    break;
            }
        }
        // END filters..

        if($onlyTotal) {
            return $fees->count();
        }

        // Ordering..
        $sOrder = (isset($search['sord']) && ($search['sord'] == 'asc' || $search['sord'] == 'desc')) ? $search['sord'] : 'asc';
        if(isset($search['sidx'])) {
            switch ($search['sidx']) {
                case 'name':
                case 'availability':
                case 'availability_unit':
                    $fees = $fees->orderBy($search['sidx'], $search['sord']);
                    break;
                case 'price':
                    $fees = $fees->orderBy($search['sidx'], $search['sord'])->orderBy('currency', $search['sord']);
                    break;

                default:
                    $fees = $fees->orderBy('name', $search['sord']);
                    break;
            }
        }

        if(!isset($search['noLimit']) || !$search['noLimit']) {
            $limit  = !isset($search['limit']) || empty($search['limit']) ? 10 : $search['limit'];
            $page   = !isset($search['page']) || empty($search['page']) ? 1 : $search['page'];
            $from   = ($page - 1)*$limit;
            $fees = $fees->take($limit)->skip($from);
        }

        return $fees->get();
    }

    public function getCache() {
        $all = $this->all();
        $toReturn = array();

        foreach($all as $fee) {
            $toReturn[$fee->id] = array(
                'name'                  =>  $fee->name,
                'is_mandatory'          =>  empty($fee->is_mandatory) ? false : true,
                'availability'          =>  $fee->availability,
                'availability_unit'     =>  $fee->availability_unit
            );
        }

        return $toReturn;
    }

    public function getFeeEndTime($duration, $unit, $startTime) {
        $startParsed = Carbon::createFromFormat('Y-m-d', $startTime);
        $endTime = $startParsed;
        switch ($unit) {
            case '1': // Month
                $endTime = $startParsed->addMonths($duration);
                break;
            case '2': // Year
                $endTime = $startParsed->addYears($duration);
                break;
        }

        return $endTime;
    }

    public function getMemberFees($memberId) {
        return $this->select('fees.name', 'fee_members.id', 'fee_members.date_paid', 'fee_members.expiration_date')
                    ->join('fee_members', 'fee_members.fee_id', '=', 'fees.id')
                    ->where('member_id', $memberId)
                    ->get();
    }

    public function getPeriod() {
        $toReturn = $this->date_paid->format('Y-m-d');

        if(!empty($this->expiration_date)) {
            $toReturn .= " - ".$this->expiration_date->format('Y-m-d');
        } else {
            $toReturn .= " - Not expiring";
        }

        return $toReturn;
    }

    public function checkFeesForSuspention(&$member) {
        $existingFeesCache = $this->getCache();

        $now = date('Y-m-d');
        $FeeMember = new FeeMember();
        $feesPaid = $FeeMember->getPaidMemberFees($member->id, $now);

        $hasFeesPaid = true;
        foreach($existingFeesCache as $key => $fee) {
            if(!$fee['is_mandatory']) {
                continue;
            }

            if(!in_array($key, $feesPaid)) {
                $hasFeesPaid = false;
            }
        }

        if($hasFeesPaid && !empty($member->is_suspended)) { // Everything is paid and member is suspended.. we unsuspend him..
            $member->unsuspendAccount();
        } elseif(!$hasFeesPaid && empty($member->is_suspended)) { // Has at least one mandatory fee not paid and he's not suspended, we suspend him..
            $member->suspendAccount("One or more mandatory fees are unpaid!");
        }

        return $hasFeesPaid;
    }
}
