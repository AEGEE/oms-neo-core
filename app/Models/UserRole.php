<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $table = "user_roles";

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
    					->join('role_module_pages', 'roles.id', '=', 'user_roles.role_id')
    					->where('user_roles.user_id', $userId)
    					->get();
    	foreach($pages as $page) {
    		$toReturn[] = $page->module_page_id;
    	}

    	return $toReturn;
    }
}
