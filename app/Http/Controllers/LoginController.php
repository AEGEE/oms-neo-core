<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Http\Requests;
use App\Http\Requests\AddUserRequest;
use App\Http\Requests\LoginRequest;

use App\Models\Auth;
use App\Models\Body;
use App\Models\Fee;
use App\Models\StudyField;
use App\Models\StudyType;
use App\Models\Member;

use App\Repositories\UserRepository;


use Hash;
use Input;
use Session;
use Socialite;
use Uuid;

class LoginController extends Controller
{
    public function loginUsingCredentials(LoginRequest $req, Auth $auth, Fee $fee) {
      Log::debug("Received login request");
    	// Todo: check if oAuth is defined..
    	$oAuthDefined = false;
    	if($oAuthDefined) {
    		$toReturn = array(
    			'success'	=>	0,
    			'message'	=>	'Please use the oAuth endpoint!'
    		);
    		return response(json_encode($toReturn), 422);
    	}

    	$auth->ip = $req->ip();
    	$auth->user_agent = $req->header('User-Agent');

    	$rawRequestParams = http_build_query($req->all());
    	$rawRequestParams = preg_replace('/password=.*/', "password=CENSORED", $rawRequestParams);

    	$auth->raw_request_params = $rawRequestParams;

    	$username = Input::get('username');
    	$password = Input::get('password');

    	$toReturn = array(
  			'success'	=>	0,
  			'message'	=>	'Username / password invalid!'
  	    );

      $user = UserRepository::getFromCredentials($username, $password);

    	if(!$user) {
        Log::debug("No user found for credentials");
    	   // No user found for credentials..
    		$auth->save();
    		return response(json_encode($toReturn), 422);
    	}

    	// We found a user..
    	$auth->user_id = $user->id;

    	// all good..
    	$loginKey = Uuid::generate(1);
    	$loginKey = $loginKey->string;

    	$auth->token_generated = $loginKey;
    	$auth->successful = 1;
    	$auth->save();

      // We check if user has all fees paid, if not, we auto-suspend him..
      $user->checkStillValid();

    	// We try to also add it to session..
      $userData = $user->getLoginUserArray($loginKey);

      Session::put('userData', $userData);
      Session::save();
    	// Mirroring Laravel session data to PHP native session.. For use with angular partials..
    	session_start();
    	$_SESSION['userData'] = Session::get('userData');
    	session_write_close();

    	$toReturn = array(
  			'success'	=>	1,
  			'message'	=>	$loginKey
  		);

      Log::debug("User login: " . $user->getName());
    	// Login successful.. returning data..
	    return response(json_encode($toReturn), 200);
    }

    public function getRegistrationFields() {
        $toReturn = array(
            'antennae'      =>  array(),
            'study_type'    =>  array(),
            'study_field'   =>  array()
        );

        foreach(Body::all() as $body) {
            $toReturn['antennae'][] = array(
                'id'    =>  $body->id,
                'name'  =>  $body->name
            );
        }

        foreach(StudyType::all() as $study_type) {
            $toReturn['study_type'][] = array(
                'id'    =>  $study_type->id,
                'name'  =>  $study_type->name
            );
        }

        foreach(StudyField::all() as $study_field) {
            $toReturn['study_field'][] = array(
                'id'    =>  $study_field->id,
                'name'  =>  $study_field->name
            );
        }

        return response(json_encode($toReturn), 200);
    }

    public function createUser() { //AddUserRequest $req, Member $usr, Auth $auth) {
        return response()->json('Not supported yet');
        //TODO: Method seems to already been in a broken state before.

        // Checking email for duplicate..
        $email = Input::get('contact_email');
        $emailHash = $usr->getEmailHash($email);
        if($usr->checkEmailHash($emailHash, 0)) {
            $toReturn['success'] = 0;
            $toReturn['message'] = "Email already exists!";
            return response(json_encode($toReturn), 200);
        }

        $usr->first_name = Input::get('first_name');
        $usr->last_name = Input::get('last_name');
        $usr->date_of_birth = Input::get('date_of_birth');
        $usr->gender = Input::get('gender');
        $usr->contact_email = $email;
        $usr->phone = Input::get('phone');
        $usr->address = Input::get('address');
        $usr->city = Input::get('city');
        $usr->antenna_id = Input::get('antenna_id');
        $usr->university = Input::get('university');
        $usr->studies_type_id = Input::get('studies_type');
        $usr->studies_field_id = Input::get('study_field');
        $usr->email_hash = $emailHash;
        $usr->save();

        $toReturn = array(
            'success'   =>  1,
        );

        return response(json_encode($toReturn), 200);
    }

    public function loginUsingOauth() {
        Log::debug("Oauth login attempt detected.");
        if(!$this->isOauthDefined()) {
            $toReturn = array(
                'success'   =>  0,
                'message'   =>  'Please use the credentials login endpoint!'
            );
            return response(json_encode($toReturn), 422);
        }
        $provider = $this->getOAuthProvider();
        $allowedDomain = $this->getOAuthAllowedDomain();
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

    public function oAuthCallback(Member $member, Auth $auth, Fee $fee, Request $req) {
        if(!$this->isOauthDefined()) {
            $toReturn = array(
                'success'   =>  0,
                'message'   =>  'Please use the credentials login endpoint!'
            );
            return response(json_encode($toReturn), 422);
        }

        // Saving login request..
        $auth->ip = $req->ip();
        $auth->user_agent = $req->header('User-Agent');

        $rawRequestParams = http_build_query($req->all());
        $rawRequestParams = preg_replace('/password=.*/', "password=CENSORED", $rawRequestParams);

        $auth->raw_request_params = $rawRequestParams;

        $toReturn = array(
            'success'   =>  0,
            'message'   =>  'Account does not exist!'
        );

        $provider = $this->getOAuthProvider();
        $oAuthUser = Socialite::driver($provider)->user();
        $userEmail = $oAuthUser->getEmail();

        try {
            $user = $user->where('internal_email', $userEmail)
                            ->whereNotNull('activated_at') // If its null, then the account was not activated..
                            ->firstOrFail();
        } catch(ModelNotFoundException $ex) {
            $auth->save();
            return response(json_encode($toReturn), 422);
        }

        // Member exists..
        $user->oauth_token = $oAuthUser->token;
        $user->save();

        // We found a user..
        $auth->member_id = $user->id;

        // all good..
        $loginKey = Uuid::generate(1);
        $loginKey = $loginKey->string;

        $auth->token_generated = $loginKey;
        $auth->successful = 1;
        $auth->save();

        // We check if user has all fees paid, if not, we auto-suspend him..
        if(empty($user->is_superadmin)) {
            $fee->checkFeesForSuspention($user);
        }

        // We try to also add it to session..
        $userData = $user->getLoginMemberArray($loginKey);

        Session::put('userData', $userData);
        // Mirroring Laravel session data to PHP native session.. For use with angular partials..
        session_start();
        $_SESSION['userData'] = Session::get('userData');
        session_write_close();

        $toReturn = array(
            'success'   =>  1,
            'message'   =>  $loginKey
        );

        // Login successful.. returning data..
        return Redirect('/');
    }
}
