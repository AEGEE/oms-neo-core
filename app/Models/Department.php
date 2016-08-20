<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = "departments";

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
}
