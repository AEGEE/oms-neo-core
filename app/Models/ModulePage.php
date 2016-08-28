<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModulePage extends Model
{
    protected $table = "module_pages";

    // Relationships..
    public function module() {
    	return $this->belongsTo('App\Models\Module', 'module_id');
    }

    public function roles() {
        return $this->belongsToMany('App\Models\Role', 'role_module_pages', 'module_page_id', 'role_id')
                    ->withPivot('permission_level');
    }

    public function roleModulePage() {
    	return $this->hasMany('App\Models\RoleModulePage');
    }

    // Model methods go down here..
    public function getFiltered($search = array(), $onlyTotal = false) {
        $modulePages = $this->select('module_pages.*', 'modules.name as module_name')
                            ->leftJoin('modules', 'module_pages.module_id', '=', 'modules.id');

        // Filters here..
        if(isset($search['name']) && !empty($search['name'])) {
            $modulePages = $modulePages->where('name', 'LIKE', "%".$search['name']."%");
        }

        if(isset($search['active']) && !empty($search['active'])) {
            switch ($search['active']) {
                case '1':
                    $modulePages = $modulePages->whereNotNull('module_pages.is_active')
                                                ->where(function($query) {
                                                    $query->whereNull('module_pages.module_id')
                                                            ->orWhere('modules.is_active', '=', 1);
                                                });
                    break;
                case '2':
                    $modulePages = $modulePages->whereNull('module_pages.is_active')
                                                ->where(function($query) {
                                                    $query->whereNull('module_pages.module_id')
                                                            ->orWhere(function($query2) {
                                                                $query2->whereNotNull('module_pages.module_id')
                                                                        ->whereNull('modules.is_active');
                                                            });
                                                });
                    break;
            }
        }

        if(isset($search['module_id']) && !empty($search['module_id'])) {
            if($search['module_id'] == -1) {
                $modulePages = $modulePages->whereNull('module_id');
            } else {
                $modulePages = $modulePages->where('module_id', $search['module_id']);
            }
        }
        // END filters..

        if($onlyTotal) {
            return $modulePages->count();
        }

        // Ordering..
        $sOrder = (isset($search['sord']) && ($search['sord'] == 'asc' || $search['sord'] == 'desc')) ? $search['sord'] : 'asc';
        if(isset($search['sidx'])) {
            switch ($search['sidx']) {
                case 'name':
                    $modulePages = $modulePages->orderBy($search['sidx'], $search['sord']);
                    break;
                case 'module_name':
                    $modulePages = $modulePages->orderBy($search['sidx'], $search['sord'])->orderBy('module_pages.name', $search['sord']);
                    break;

                default:
                    $modulePages = $modulePages->orderBy('name', $search['sord']);
                    break;
            }
        }

        if(!isset($search['noLimit']) || !$search['noLimit']) {
            $limit  = !isset($search['limit']) || empty($search['limit']) ? 10 : $search['limit'];
            $page   = !isset($search['page']) || empty($search['page']) ? 1 : $search['page'];
            $from   = ($page - 1)*$limit;
            $modulePages = $modulePages->take($limit)->skip($from);
        }

        return $modulePages->get();
    }

    public function getModulePagesCache() {
        $pagesCache = array();
        $modulePages = $this->with('module')->get();
        foreach($modulePages as $modulePage) {
            $moduleName = empty($modulePage->module_id) ? "Core" : $modulePage->module->name;
            $pagesCache[$modulePage->id] = $modulePage->name."(".$moduleName.")";
        }
        return $pagesCache;
    }
}
