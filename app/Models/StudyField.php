<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyField extends Model
{
    protected $table = "study_fields";

    // Relationships..
	public function studies() {
        return $this->hasMany('App\Models\Study');
    }

    public function users() {
        return $this->belongsToMany('App\Models\User', 'studies', 'study_field_id', 'user_id');
    }

    public function universities() {
        return $this->belongsToMany('App\Models\University', 'studies', 'study_field_id', 'university_id');
    }

    // Model methods go down here..
}
