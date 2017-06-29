<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $table = "positions";

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    // Relationships..
    public function memberships() {
        return $this->hasMany('App\Models\BodyMembershipCircle');
    }
}
