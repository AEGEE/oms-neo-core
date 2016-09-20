<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecrutementCampaign extends Model
{
    protected $table = "recrutement_campaigns";

    protected $dates = ['created_at', 'updated_at', 'start_date', 'end_date'];

    // Relationships..
    public function antenna() {
        return $this->belongsTo('App\Models\Antenna');
    }

    public function recrutedUser() {
        return $this->hasMany('App\Models\RecrutedUser');
    }

    // Model methods go down here..

    public function getFiltered($search = array(), $onlyTotal = false) {
    	$campaign = $this->select('antennas.name', 'recrutement_campaigns.*')->join('antennas', 'antennas.id', '=', 'recrutement_campaigns.antenna_id');

    	// Filters here..
    	if(isset($search['antenna_id']) && !empty($search['antenna_id'])) {
    		$campaign = $campaign->where('antenna_id', $search['antenna_id']);
    	}

    	if(isset($search['start_date']) && !empty($search['start_date'])) {
    		$campaign = $campaign->where('start_date', '>=', $search['start_date']);
    	}

    	if(isset($search['end_date']) && !empty($search['end_date'])) {
    		$campaign = $campaign->where('end_date', '<=', $search['end_date']);
    	}
    	// END filters..

    	if($onlyTotal) {
    		return $campaign->count();
    	}

    	// Ordering..
    	$sOrder = (isset($search['sord']) && ($search['sord'] == 'asc' || $search['sord'] == 'desc')) ? $search['sord'] : 'asc';
    	if(isset($search['sidx'])) {
			switch ($search['sidx']) {
				case 'name':
				case 'start_date':
				case 'end_date':
					$campaign = $campaign->orderBy($search['sidx'], $search['sord']);
					break;

				default:
					$campaign = $campaign->orderBy('name', $search['sord']);
					break;
			}
    	}

		if(!isset($search['noLimit']) || !$search['noLimit']) {
			$limit 	= !isset($search['limit']) || empty($search['limit']) ? 10 : $search['limit'];
			$page 	= !isset($search['page']) || empty($search['page']) ? 1 : $search['page'];
			$from 	= ($page - 1)*$limit;
			$campaign = $campaign->take($limit)->skip($from);
		}

		return $campaign->get();
    }

    private function createRandomString($max_chars = 6) {
        $str = str_shuffle("asdfghjklqwertyuiop1234567890zxcvbnm");
        return substr($str, 0, $max_chars);
    }

    public function createRandomLink($max_chars = 6) {
        do {
            $string = $this->createRandomString();
            $alreadyExists = $this->where('link', $string)->count();
        } while($alreadyExists != 0);

        return $string;
    }
}
