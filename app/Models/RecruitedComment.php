<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecruitedComment extends Model
{
    protected $table = "recruited_comments";

    // Relationships..
    public function recruitment_campaigns() {
        return $this->belongsTo('App\Models\RecruitedMember');
    }

    public function member() {
        return $this->belongsTo('App\Models\Member', 'member_id');
    }

    // Model methods go down here..
}
