<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
	protected $table = "news";

    // Relationships..
    public function user() {
    	return $this->belongsTo('App\Models\User');
    }

    // Model methods go down here..
    public function getFiltered($search = array(), $onlyTotal = false) {
        $news = $this->with('user');

        // Filters here..

        // END filters..

        if($onlyTotal) {
            return $news->count();
        }

        // Ordering..
        $sOrder = (isset($search['sord']) && ($search['sord'] == 'asc' || $search['sord'] == 'desc')) ? $search['sord'] : 'asc';
        if(isset($search['sidx'])) {
            switch ($search['sidx']) {
                case 'created_at':
                    $news = $news->orderBy($search['sidx'], $search['sord']);
                    break;

                default:
                    $news = $news->orderBy('created_at', $search['sord']);
                    break;
            }
        }

        if(!isset($search['noLimit']) || !$search['noLimit']) {
            $limit  = !isset($search['limit']) || empty($search['limit']) ? 10 : $search['limit'];
            $page   = !isset($search['page']) || empty($search['page']) ? 1 : $search['page'];
            $from   = ($page - 1)*$limit;
            $news = $news->take($limit)->skip($from);
        }

        return $news->get();
    }
}
