<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Auth extends Model
{
    protected $table = "auths";

    // Relationships..
    public function user() {
    	return $this->belongsTo('App\Models\Member');
    }

    // Model methods go down here..
    public function isUserLogged($xApiKey = '') {
    	if(empty($xApiKey)) {
    		return false;
    	}

    	$now = date('Y-m-d H:i:s');

    	try {
    		$auth = $this->where('token_generated', $xApiKey)
                        ->where(function($query) use($now) {
                            $query->where('expiration', '>', $now)
                                    ->orWhereNull('expiration');
                        })
                        ->firstOrFail();
        } catch(ModelNotFoundException $ex) {
            return false;
        }

        return true;
    }
}
