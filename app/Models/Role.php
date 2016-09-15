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

    public function user() {
        return $this->belongsToMany('App\Models\User', 'user_roles', 'role_id', 'user_id');
    }

    public function roleModulePages() {
    	return $this->hasMany('App\Models\RoleModulePage');
    }

    public function userRole() {
    	return $this->hasMany('App\Models\UserRole');
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

    public function getUserRoles($userId) {
        return $this->select('roles.name', 'user_roles.id')
                    ->join('user_roles', 'user_roles.role_id', '=', 'roles.id')
                    ->where('user_id', $userId)
                    ->whereNull('is_disabled')
                    ->get();
    }
}
