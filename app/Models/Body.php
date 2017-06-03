<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Body extends Model
{
    protected $table = "bodies";

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

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
    public function getFiltered($search = array()) {
        //TODO reimplement filters.
        return Body::with(['address' => function ($q) { $q->with('country');}])->get();
    }
}
