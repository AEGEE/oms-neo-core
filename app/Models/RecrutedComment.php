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

    // Model methods go down here..
}
