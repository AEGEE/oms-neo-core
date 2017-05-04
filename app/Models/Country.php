<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = "countries";

    // Relationships..
    public function addresses() {
        return $this->hasMany('App\Models\Address');
    }

    public function users() {
        return $this->hasManyThrough('App\Models\User', 'App\Models\Address', 'country_id', 'address_id', 'id');
    }

    public function universities() {
        return $this->hasManyThrough('App\Models\University', 'App\Models\Address', 'country_id', 'address_id', 'id');
    }

    public function bodies() {
        return $this->hasManyThrough('App\Models\Body', 'App\Models\Address', 'country_id', 'address_id', 'id');
    }

    // Model methods go down here..
}
