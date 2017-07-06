<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Util;

class Circle extends Model
{
    protected $table = "circles";
    protected $guarded = ["id"];

    public function setNameAttribute($value) {
        $this->attributes['name_simple'] = Util::limitCharacters($value);
        $this->attributes['name'] = $value;
    }

    // Relationships..
    public function body() {
    	return $this->belongsTo('App\Models\Body');
    }

    public function memberships() {
    	return $this->hasMany('App\Models\CircleMembership');
    }

    public function users() {
        return $this->belongsToMany('App\Models\User', 'circle_memberships', 'circle_id', 'user_id');
    }

    public function parentCircle() {
        return $this->belongsTo('App\Models\Circle', 'parent_id', 'id');
    }

    public function childrenCircles() {
        return $this->hasMany('App\Models\Circle', 'parent_id', 'id');
    }


    public function hasParent() {
        return $this->parentCircle != null;
    }

    public function hasChildren() {
        return $this->childrenCircles != null;
    }

    public function getChildrenRecursive() {
        return collect([$this])->merge($this->childrenCircles->flatMap(function ($circle) { return $circle->getChildrenRecursive(); }));
    }

    public function getParentsRecursive() {
        if ($this->hasParent()) {
            return collect([$this])->merge($this->parentCircle->getParentsRecursive());
        } else {
            return collect([$this]);
        }
    }
}
