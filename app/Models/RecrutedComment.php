<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecrutedComment extends Model
{
    protected $table = "recruted_comments";

    // Relationships..
    public function recrutement_campaigns() {
        return $this->belongsTo('App\Models\RecrutedUser');
    }

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    // Model methods go down here..
}
