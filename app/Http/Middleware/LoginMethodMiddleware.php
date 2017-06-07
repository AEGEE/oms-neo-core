<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;

class LoginMethodMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $loginMethod)
    {
        if ($loginMethod == 'oauth') {
            if ($this->isOauthDefined()) {
                //oAuth login method.

                $request->provider = $this->getOAuthProvider();
                $request->allowedDomain = $this->getOAuthAllowedDomain();
                
                return $next($request);
            } else {
                return response()->failure("Please use credentials as login method.");
            }
        } else {
            //Credentials login method.
            return $next($request);
        }
    }

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
}
