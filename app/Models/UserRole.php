<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $table = "user_roles";

    protected $fillable = ['user_id', 'role_id'];

    // Relationships..
    public function user() {
    	return $this->belongsTo('App\Models\User');
    }

    public function role() {
    	return $this->belongsTo('App\Models\Role');
    }

    // Model methods go down here..
    public function getModulePagesIdForUser($userId) {
    	$toReturn = array();
    	$pages = $this->select('role_module_pages.*')
    					->join('roles', 'roles.id', '=', 'user_roles.role_id')
    					->join('role_module_pages', 'roles.id', '=', 'role_module_pages.role_id')
    					->where('user_roles.user_id', $userId)
    					->get();
    	foreach($pages as $page) {
            if(isset($toReturn[$page->module_page_id]) && $toReturn[$page->module_page_id] == 1) { // taking the highest permission..
                continue;
            }
    		$toReturn[$page->module_page_id] = empty($page->permission_level) ? '0' : '1';
    	}

    	return $toReturn;
    }

    public function getMaxPermissionLevelForRole($page_code, $user_id) {
        $maxLevel = $this
                        ->join('roles', 'roles.id', '=', 'user_roles.role_id')
                        ->join('role_module_pages', 'roles.id', '=', 'role_module_pages.role_id')
                        ->join('module_pages', 'module_pages.id', '=', 'role_module_pages.module_page_id')
                        ->where('user_id', $user_id)
                        ->where('module_pages.code', $page_code)
                        ->max('role_module_pages.permission_level');
        return $maxLevel;
    }
}
