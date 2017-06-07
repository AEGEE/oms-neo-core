<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = "roles";

    // Relationships..
    public function modulePages() {
        return $this->belongsToMany('App\Models\ModulePage', 'role_module_pages', 'role_id', 'module_page_id')
                    ->withPivot('permission_level');
    }

    public function member() {
        return $this->belongsToMany('App\Models\Member', 'member_roles', 'role_id', 'member_id');
    }

    public function roleModulePages() {
    	return $this->hasMany('App\Models\RoleModulePage');
    }

    public function memberRole() {
    	return $this->hasMany('App\Models\MemberRole');
    }

    // Model methods go down here..
    public function getFiltered($search = array(), $onlyTotal = false) {
        $roles = $this->with('roleModulePages');

        // Filters here..
        if(isset($search['name']) && !empty($search['name'])) {
            $roles = $roles->where('name', 'LIKE', "%".$search['name']."%");
        }
        // END filters..

        if($onlyTotal) {
            return $roles->count();
        }

        // Ordering..
        $sOrder = (isset($search['sord']) && ($search['sord'] == 'asc' || $search['sord'] == 'desc')) ? $search['sord'] : 'asc';
        if(isset($search['sidx'])) {
            switch ($search['sidx']) {
                case 'name':
                    $roles = $roles->orderBy($search['sidx'], $search['sord']);
                    break;

                default:
                    $roles = $roles->orderBy('name', $search['sord']);
                    break;
            }
        }

        if(!isset($search['noLimit']) || !$search['noLimit']) {
            $limit  = !isset($search['limit']) || empty($search['limit']) ? 10 : $search['limit'];
            $page   = !isset($search['page']) || empty($search['page']) ? 1 : $search['page'];
            $from   = ($page - 1)*$limit;
            $roles = $roles->take($limit)->skip($from);
        }

        return $roles->get();
    }

    public function getCache() {
        $all = $this->all();
        $toReturn = array();

        foreach($all as $role) {
            $toReturn[$role->id] = $role->name;
        }

        return $toReturn;
    }

    public function getMemberRoles($memberId) {
        return $this->select('roles.name', 'member_roles.id')
                    ->join('member_roles', 'member_roles.role_id', '=', 'roles.id')
                    ->where('member_id', $memberId)
                    ->whereNull('is_disabled')
                    ->get();
    }
}
