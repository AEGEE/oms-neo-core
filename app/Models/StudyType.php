<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyType extends Model
{
    protected $table = "study_types";

    // Relationships..
	public function studies() {
        return $this->hasMany('App\Models\Study');
    }

    public function users() {
        return $this->belongsToMany('App\Models\User', 'studies', 'study_type_id', 'user_id');
    }

    public function universities() {
        return $this->belongsToMany('App\Models\University', 'studies', 'study_type_id', 'university_id');
    }

    // Model methods go down here..
}
