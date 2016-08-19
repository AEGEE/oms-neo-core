<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Http\Requests;
use App\Http\Requests\LoginRequest;

use App\Models\Auth;
use App\Models\User;

use Hash;
use Input;
use Session;
use Uuid;

class LoginController extends Controller
{
    public function loginUsingCredentials(LoginRequest $req, User $user, Auth $auth) {
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

    	// Trying to find a user..
    	// Non oAuth logins are handled by contact_email field..
    	try {
    		$user = $user->where('contact_email', $username)
    					->whereNotNull('password')
    					->whereNull('is_suspended')
    					->firstOrFail(); // If password is null, then account should be used with oAuth..
    	} catch(ModelNotFoundException $ex) {
    		$auth->save();
    		return response(json_encode($toReturn), 422);
    	}

    	// We found a user..
    	$auth->user_id = $user->id;

    	// Invalid password..
    	if(!Hash::check($password, $user->password)) {
    		$auth->save();
    		return response(json_encode($toReturn), 422);
    	}


    	// all good..
    	$loginKey = Uuid::generate(1);
    	$loginKey = $loginKey->string;

    	$auth->token_generated = $loginKey;
    	$auth->successful = 1;
    	$auth->save();

    	// We try to also add it to session..
    	$userData = array(
    		'username'			=>	$user->contact_email,
    		'fullname'			=>	$user->first_name." ".$user->last_name,
    		'is_superadmin'		=>	$user->is_superadmin,
    		'department_id'		=>	$user->department_id,
            'logged_in'         =>  true,
            'authToken'         =>  $loginKey
    	);

		Session::put('userData', $userData);
    	// Mirroring Laravel session data to PHP native session.. For use with angular partials..
    	session_start();
    	$_SESSION['userData'] = Session::get('userData');
    	session_write_close();    	

    	$toReturn = array(
			'success'	=>	1,
			'message'	=>	$loginKey
		);

    	// Login successful.. returning data..
		return response(json_encode($toReturn), 200);
    }
}
