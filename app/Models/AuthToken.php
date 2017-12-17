<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class AuthToken extends Model
{
    protected $table = "auth_tokens";

    // Relationships..
    public function user() {
    	return $this->belongsTo('App\Models\User');
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

    public function prepareAuthToken(Request $req){
        $this->ip_address = $req->ip();
        $this->user_agent = $req->header('User-Agent');

        $rawRequestParams = http_build_query($req->all());
        $rawRequestParams = preg_replace('/password=.*/', "password=CENSORED", $rawRequestParams);

        $this->raw_request_params = $rawRequestParams;
        $this->save();

        return $this;
    }

    public function completeAuthToken($user_id, $token) {
        $this->user_id = $user_id;
        $this->token_generated = $token;
        $this->successful = 1;
        $this->expiration = date('Y-m-d H:i:s', time() + 60 * 60 * 1); //valid for 1 hour.
        $this->save();

        return $this;
    }
}
