<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Antenna;

use Session;

class GenericController extends Controller
{
    public function test(Antenna $ant) {
    	return $ant->with('country')->find(1);
    }

    public function defaultRoute() {
    	$userData = Session::get('userData');

    	if($userData['logged_in']) {
            $addToView = array();
            $addToView['userData'] = $userData;
    		return view('loggedIn', $addToView);
    	}
		return view('notLogged');
    }

    public function logout() {
        Session::flush();
        session_start();
        session_destroy();
        return redirect('/');
    }
}
