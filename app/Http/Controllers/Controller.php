<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;

use File;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    protected function isOauthDefined() {
    	$oAuth = config('oauth.oAuthProvider');
        $oAuthDefined = false;
        if(!empty($oAuth)) {
            $oAuthDefined = true;
        }

        return $oAuthDefined;
    }

    protected function getOAuthProvider() {
    	$oAuth = config('oauth.oAuthProvider');
        return $oAuth;
    }

    protected function getDelegatedAdmin() {
        return config('oauth.oAuthAdmin');
    }

    protected function getOAuthAllowedDomain() {
    	$oAuthDomain = config('oauth.oAuthDomain');
    	return $oAuthDomain;
    }

    protected function getOauthCredentials() {
        $path = storage_path()."/app/".config('oauth.credentials').".json";
        return json_decode(File::get($path), true);
    }
}
