<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkingGroup extends Model
{
    protected $table = "working_groups";

    protected $dates = ['created_at', 'updated_at', 'start_date', 'end_date'];

    // Relationships..
    public function userWorkingGroup() {
    	return $this->hasMany('App\Models\MemberWorkingGroup');
    }

    public function users() {
        return $this->belongsToMany('App\Models\Member', 'user_working_groups', 'work_group_id', 'user_id')
                    ->withPivot('start_date', 'end_date');
    }

    // Model methods go down here..
    public function getFiltered($search = array(), $onlyTotal = false) {
        $workGroups = $this;

        // Filters here..
        if(isset($search['name']) && !empty($search['name'])) {
            $workGroups = $workGroups->where('name', 'LIKE', "%".$search['name']."%");
        }
        // END filters..

        if($onlyTotal) {
            return $workGroups->count();
        }

        // Ordering..
        $sOrder = (isset($search['sord']) && ($search['sord'] == 'asc' || $search['sord'] == 'desc')) ? $search['sord'] : 'asc';
        if(isset($search['sidx'])) {
            switch ($search['sidx']) {
                case 'name':
                case 'description':
                    $workGroups = $workGroups->orderBy($search['sidx'], $search['sord']);
                    break;

                default:
                    $workGroups = $workGroups->orderBy('name', $search['sord']);
                    break;
            }
        }

        if(!isset($search['noLimit']) || !$search['noLimit']) {
            $limit  = !isset($search['limit']) || empty($search['limit']) ? 10 : $search['limit'];
            $page   = !isset($search['page']) || empty($search['page']) ? 1 : $search['page'];
            $from   = ($page - 1)*$limit;
            $workGroups = $workGroups->take($limit)->skip($from);
        }

        return $workGroups->get();
    }

    public function getMemberWorkingGroups($userId) {
        $wg = $this->select('working_groups.name', 'user_working_groups.id', 'user_working_groups.start_date', 'user_working_groups.end_date')
                    ->join('user_working_groups', 'user_working_groups.work_group_id', '=', 'working_groups.id')
                    ->where('user_id', $userId)
                    ->get();
        return $wg;
    }

    public function getPeriod() {
        $toReturn = "";
        if(empty($this->start_date)) {
           return $toReturn; 
        }

        $toReturn = $this->start_date->format('Y-m-d');

        if(!empty($this->end_date)) {
            $toReturn .= " - ".$this->end_date->format('Y-m-d');
        }

        return $toReturn;
    }
}
