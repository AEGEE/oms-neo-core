<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlobalOption extends Model
{
    protected $table = "global_options";

    // Relationships..

    // Model methods go down here..
    public function getFiltered($search = array(), $onlyTotal = false) {
        $options = $this;

        // Filters here..
        if(isset($search['name']) && !empty($search['name'])) {
            $options = $options->where('name', 'LIKE', "%".$search['name']."%");
        }

        if(isset($search['not_editable']) && !empty($search['not_editable'])) {
        	switch ($search['not_editable']) {
        		case '1':
        			$options = $options->whereNotNull('not_editable');
        			break;
        		case '0':
        			$options = $options->whereNull('not_editable');
        			break;
        	}
            
        }
        // END filters..

        if($onlyTotal) {
            return $options->count();
        }

        // Ordering..
        $sOrder = (isset($search['sord']) && ($search['sord'] == 'asc' || $search['sord'] == 'desc')) ? $search['sord'] : 'asc';
        if(isset($search['sidx'])) {
            switch ($search['sidx']) {
                case 'name':
                    $options = $options->orderBy($search['sidx'], $search['sord']);
                    break;

                default:
                    $options = $options->orderBy('name', $search['sord']);
                    break;
            }
        }

        if(!isset($search['noLimit']) || !$search['noLimit']) {
            $limit  = !isset($search['limit']) || empty($search['limit']) ? 10 : $search['limit'];
            $page   = !isset($search['page']) || empty($search['page']) ? 1 : $search['page'];
            $from   = ($page - 1)*$limit;
            $options = $options->take($limit)->skip($from);
        }

        return $options->get();
    }

    public function getOptionsArray() {
        $options = $this->all();
        $toReturn = array();
        foreach($options as $option) { 
            $toReturn[$option->code] = $option->value;
        }

        return $toReturn;
    }
}
