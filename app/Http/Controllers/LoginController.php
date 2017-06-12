<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Http\Requests;
use App\Http\Requests\AddUserRequest;
use App\Http\Requests\LoginRequest;

use App\Models\AuthToken;
use App\Models\StudyField;
use App\Models\StudyType;
use App\Models\User;

use Auth;
use Hash;
use Input;
use Session;
use Socialite;
use Uuid;
use Log;

class LoginController extends Controller
{
    public function loginUsingCredentials(LoginRequest $req, AuthToken $auth) {
        $auth->prepareAuthToken($req);

        //By using Auth::attempt instead of Auth::once the login endpoint is no longer stateless.
        //This is a workaround until the frontend is capable of keeping its own sessions.
        if (!Auth::attempt(['personal_email' => $req->username, 'password' => $req->password])) {
            return response()->credentialsFailure();
        }
        $user = Auth::user();
        if ($user->activated_at == null) {
            Auth::logout();
            return response()->failure('User not activated.');
        }
        $loginKey = Uuid::generate(1)->string;

        $auth->completeAuthToken($user->id, $loginKey);
        $auth->save();

        // Login successful.. returning data..
        return response()->success($loginKey, null, "User login token");
    }



    public function loginUsingOauth(Request $req) {
        $provider = $req->provider;
        $allowedDomain = $req->allowedDomain;
        if($provider == 'google' && !empty($allowedDomain)) { // Google supports single domain
            return Socialite::driver($provider)->with(['hd' => $allowedDomain])->scopes(['https://www.googleapis.com/auth/admin.directory.user'])->redirect();
        }
        if($provider == 'live') {
            return Socialite::with($provider)->redirect();
        }
        if ($provider == 'azure') {
            return Socialite::driver($provider)->with(['hd' => $allowedDomain])->scopes(['https://graph.microsoft.com/Directory.ReadWrite.All'])->redirect();
        }
        if ($provider == 'graph') {
            return Socialite::driver($provider)->with(['hd' => $allowedDomain])->scopes(['https://graph.microsoft.com/Directory.ReadWrite.All', 'https://graph.microsoft.com/User.Read.All', 'https://graph.microsoft.com/Group.Read.All', 'https://graph.microsoft.com/Mail.Send'])->redirect();
        }
        return Socialite::driver($provider)->redirect();
    }

    public function oAuthCallback(AuthToken $auth, Request $req) {
        // Saving login request..
        $auth->prepareAuthToken($req);

        $provider = $req->provider;
        $oAuthUser = Socialite::driver($provider)->user();

        //dd($oAuthUser);

        Log::debug($oAuthUser->token);

        $userEmail = $oAuthUser->getEmail();

        try {
            $user = User::where('internal_email', $userEmail)
                            ->whereNotNull('activated_at') // If its null, then the account was not activated..
                            ->firstOrFail();
        } catch(ModelNotFoundException $ex) {
            return response()->failure('User not found.');
        }

        // User exists..
        $user->oauth_token = $oAuthUser->token;
        // TODO Might need to be adjusted for timezones.
        $expires = time() + $oAuthUser->expiresIn;
        $user->oauth_expiration = date('Y-m-d H:i:s', $expires);
        $user->save();

        $loginKey = Uuid::generate(1)->string;

        $auth->completeAuthToken($user->id, $loginKey);

        Auth::login($user);

        // Login successful.. returning data..
        return response()->success($loginKey, null, 'User login token');
    }
}
