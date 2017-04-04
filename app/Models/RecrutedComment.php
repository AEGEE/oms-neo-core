<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecrutedComment extends Model
{
    protected $table = "recruted_comments";

    // Relationships..
    public function recrutement_campaigns() {
        return $this->belongsTo('App\Models\RecrutedMember');
    }

    public function member() {
        return $this->belongsTo('App\Models\Member', 'member_id');
    }

    // Model methods go down here..
}
