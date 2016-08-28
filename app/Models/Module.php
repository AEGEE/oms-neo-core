<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $table = "modules";

    // Relationships..
    public function proxyRequestFrom() {
    	return $this->hasMany('App\Models\ProxyRequest', 'from_module_id');
    }

    public function proxyRequestTo() {
    	return $this->hasMany('App\Models\ProxyRequest', 'to_module_id');
    }

    public function modulePage() {
    	return $this->hasMany('App\Models\ModulePage');
    }

    // Model methods go down here..
    public function getFiltered($search = array(), $onlyTotal = false) {
        $modules = $this;

        // Filters here..
        if(isset($search['name']) && !empty($search['name'])) {
            $modules = $modules->where('name', 'LIKE', "%".$search['name']."%");
        }

        if(isset($search['active']) && !empty($search['active'])) {
            switch ($search['active']) {
                case '1':
                    $modules = $modules->whereNotNull('is_active');
                    break;
                case '2':
                    $modules = $modules->whereNull('is_active');
                    break;
            }
        }
        // END filters..

        if($onlyTotal) {
            return $modules->count();
        }

        // Ordering..
        $sOrder = (isset($search['sord']) && ($search['sord'] == 'asc' || $search['sord'] == 'desc')) ? $search['sord'] : 'asc';
        if(isset($search['sidx'])) {
            switch ($search['sidx']) {
                case 'name':
                    $modules = $modules->orderBy($search['sidx'], $search['sord']);
                    break;

                default:
                    $modules = $modules->orderBy('name', $search['sord']);
                    break;
            }
        }

        if(!isset($search['noLimit']) || !$search['noLimit']) {
            $limit  = !isset($search['limit']) || empty($search['limit']) ? 10 : $search['limit'];
            $page   = !isset($search['page']) || empty($search['page']) ? 1 : $search['page'];
            $from   = ($page - 1)*$limit;
            $modules = $modules->take($limit)->skip($from);
        }

        return $modules->get();
    }
}
