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

class LoginController extends Controller
{
    public function loginUsingCredentials(LoginRequest $req) {
        $auth = $this->generateAuthToken($req);

        //By using Auth::attempt instead of Auth::once the login endpoint is no longer stateless.
        //This is a workaround until the frontend is capable of keeping its own sessions.
        if (!Auth::attempt(['contact_email' => $req->username, 'password' => $req->password])) {
            return response()->credentialsFailure();
        }
        $user = Auth::user();
        if ($user->activated_at == null) {
            Auth::logout();
            return response()->failure('User not activated.');
        }
        $loginKey = Uuid::generate(1)->string;

        $this->completeAuthToken($auth, $user->id, $loginKey);
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
        return Socialite::driver($provider)->redirect();
    }

    public function oAuthCallback(User $user, AuthToken $auth, Request $req) {
        // Saving login request..
        $auth = generateAuthToken();

        $provider = $req->provider;
        $oAuthUser = Socialite::driver($provider)->user();
        $userEmail = $oAuthUser->getEmail();

        try {
            //TODO internal_email is no longer a thing.
            $user = $user->where('internal_email', $userEmail)
                            ->whereNotNull('activated_at') // If its null, then the account was not activated..
                            ->firstOrFail();
        } catch(ModelNotFoundException $ex) {
            $auth->save();
            return response()->failure('User not found.');
        }

        // User exists..
        $user->oauth_token = $oAuthUser->token;
        $user->save();
        //TODO login user?

        $loginKey = Uuid::generate(1)->string;

        $this->completeAuthToken($auth, $user->id, $loginKey);
        $auth->save();

        // Login successful.. returning data..
        return response()->success($loginKey, null, 'User login token');
    }

    //TODO Move these methods to AuthToken.php?
    public function generateAuthToken(Request $req){
        $auth = new AuthToken;
        $auth->ip_address = $req->ip();
        $auth->user_agent = $req->header('User-Agent');

        $rawRequestParams = http_build_query($req->all());
        $rawRequestParams = preg_replace('/password=.*/', "password=CENSORED", $rawRequestParams);

        $auth->raw_request_params = $rawRequestParams;
        return $auth;
    }

    public function completeAuthToken(AuthToken &$auth, $user_id, $token) {
        $auth->user_id = $user_id;
        $auth->token_generated = $token;
        $auth->successful = 1;
        return $auth;
    }
}
