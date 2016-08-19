<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Login route..
Route::post('/api/login', 'LoginController@loginUsingCredentials');

// Core api routes..
Route::group(['middleware' => 'api'], function() {
	// Routes go in here..
	Route::post('/api/testMiddleware', function() {
		print_r(Request::get('userData'));
		return 'hello!';
	});
});

// Generic routes.. TODO
// Route::get('/', function () {
//     return view('template');
// });

Route::any('/logout', 'GenericController@logout');

// ALL ROUTES SHOULD GO BEFORE THIS ONE!
Route::any('{all}', array('uses' => 'GenericController@defaultRoute'))->where('all', '.*');
