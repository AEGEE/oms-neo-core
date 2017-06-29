<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Util;

class Body extends Model
{
    protected $table = "bodies";

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function setNameAttribute($value) {
        $this->attributes['name_simple'] = Util::encodeSimple($value);
        $this->attributes['name'] = $value;
    }

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


    public function scopeFilterName($query, $name) {
        if (!empty($name)) {
            return $query->where(DB::raw('LOWER(name)'), 'LIKE', "%" . strtolower($name) . "%");
        } else {
            return $query;
        }
    }

    public function scopeFilterCity($query, $city) {
        if (!empty($city)) {
            return $query->select('bodies.*')
            ->rightJoin('addresses', 'bodies.address_id', '=', 'addresses.id')
            ->where(DB::raw('LOWER(addresses.city)'), 'LIKE', "%" . strtolower($city) . "%");
        } else {
            return $query;
        }
    }

    public function scopeFilterCountryID($query, $country_id) {
        if (!empty($country_id)) {
            return $query->select('bodies.*')
            ->rightJoin('addresses', 'bodies.address_id', '=', 'addresses.id')
            ->where('addresses.country_id', $country_id);
        } else {
            return $query;
        }
    }

    public function scopeFilterCountryName($query, $country_name) {
        if (!empty($country_name)) {
            return $query->select('bodies.*')
            ->rightJoin('addresses', 'bodies.address_id', '=', 'addresses.id')
            ->rightJoin('countries', 'addresses.country_id', '=', 'countries.id')
            ->where('countries.name', $country_name);
        } else {
            return $query;
        }
    }

    public function scopeFilterTypeID($query, $type_id) {
        if (!empty($type_id)) {
            return $query->where('type_id', $type_id);
        } else {
            return $query;
        }
    }

    // Model methods go down here..
    public function scopeFilterArray($query, $search = array()) {
        $query->filterName($search['name'] ?? '')
            ->filterCity($search['city'] ?? '')
            ->filterTypeID($search['type_id'] ?? '')
            ->filterCountryID($search['country_id'] ?? '')
            ->filterCountryName($search['country_name'] ?? '');

        return $query;
    }
}
