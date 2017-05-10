<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Body extends Model
{
    protected $table = "bodies";

    // Relationships..
    public function address() {
    	return $this->belongsTo('App\Models\Address');
    }

    public function users() {
    	return $this->belongsToMany('App\Models\User', 'body_memberships', 'body_id', 'user_id');
    }

    public function bodyType() {
        return $this->belongsTo('App\Models\BodyType', 'type_id');
    }

    public function bodyCircles() {
        return $this->hasMany('App\Models\BodyCircle', 'body_id', 'id');
    }

    public function globalCircles() {
        return $this->hasMany('App\Models\GlobalCircle', 'body_circles', 'body_id', 'global_circle_id');
    }

    // Model methods go down here..
    public function getFiltered($search = array(), $onlyTotal = false) {
        $bodies = $this->with('address');

    	// Filters here..
        /* FILTERS DISABLED.
        //TODO reimplement filters.
    	if(isset($search['name']) && !empty($search['name'])) {
            $bodies = $bodies->where('name', 'LIKE', "%".$search['name']."%");
        }
        if(isset($search['city']) && !empty($search['city'])) {
            $bodies = $bodies->where('city', 'LIKE', "%".$search['city']."%");
        }

        if(isset($search['country_id']) && !empty($search['country_id'])) {
            $bodies = $bodies->where('country_id', $search['country_id']);
        }
    	// END filters..

    	if($onlyTotal) {
    		return $antennae->count();
    	}

    	// Ordering..
    	$sOrder = (isset($search['sord']) && ($search['sord'] == 'asc' || $search['sord'] == 'desc')) ? $search['sord'] : 'asc';
    	if(isset($search['sidx'])) {
			switch ($search['sidx']) {
				case 'name':
                case 'city':
                case 'email':
                case 'address':
				case 'phone':
					$antennae = $antennae->orderBy($search['sidx'], $search['sord']);
					break;

				default:
					$antennae = $antennae->orderBy('name', $search['sord']);
					break;
			}
    	}

		if(!isset($search['noLimit']) || !$search['noLimit']) {
			$limit 	= !isset($search['limit']) || empty($search['limit']) ? 10 : $search['limit'];
			$page 	= !isset($search['page']) || empty($search['page']) ? 1 : $search['page'];
			$from 	= ($page - 1)*$limit;
			$antennae = $antennae->take($limit)->skip($from);
		}
        */

		return Body::all();
    }
}
