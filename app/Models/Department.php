<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class Department extends Model
{
    protected $table = "departments";

    protected $dates = ['created_at', 'updated_at', 'start_date', 'end_date'];

    // Relationships..
    public function user() {
    	return $this->hasMany('App\Models\User');
    }

    public function boardMembers() {
    	return $this->hasMany('App\Models\BoardMember');
    }

    // Model methods go down here..
    public function getFiltered($search = array(), $onlyTotal = false) {
        $departments = $this;

        // Filters here..
        if(isset($search['name']) && !empty($search['name'])) {
            $departments = $departments->where('name', 'LIKE', "%".$search['name']."%");
        }
        // END filters..

        if($onlyTotal) {
            return $departments->count();
        }

        // Ordering..
        $sOrder = (isset($search['sord']) && ($search['sord'] == 'asc' || $search['sord'] == 'desc')) ? $search['sord'] : 'asc';
        if(isset($search['sidx'])) {
            switch ($search['sidx']) {
                case 'name':
                case 'description':
                    $departments = $departments->orderBy($search['sidx'], $search['sord']);
                    break;

                default:
                    $departments = $departments->orderBy('name', $search['sord']);
                    break;
            }
        }

        if(!isset($search['noLimit']) || !$search['noLimit']) {
            $limit  = !isset($search['limit']) || empty($search['limit']) ? 10 : $search['limit'];
            $page   = !isset($search['page']) || empty($search['page']) ? 1 : $search['page'];
            $from   = ($page - 1)*$limit;
            $departments = $departments->take($limit)->skip($from);
        }

        return $departments->get();
    }

    public function getUserBoardMemberships($userId) {
        $memberships = $this->select('departments.name', 'board_members.id', 'board_members.start_date', 'board_members.end_date')
                            ->join('board_members', 'board_members.department_id', '=', 'departments.id')
                            ->where('user_id', $userId)
                            ->get();
        return $memberships;
    }

    public function getPeriod() {
        return $this->start_date->format('Y-m-d')." - ".$this->end_date->format('Y-m-d');
    }

    public function isActiveMembership() {
        return Carbon::now()->between($this->start_date, $this->end_date);
    }
}
