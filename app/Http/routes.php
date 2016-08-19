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
	
	// Antennae management..
	Route::group(['middleware' => 'checkAccess:antennae_management'], function() {
		Route::get('/api/getAntennaeForGrid', 'AntennaController@getAntennaeForGrid');
		Route::post('/api/saveAntenna', 'AntennaController@saveAntenna');
		Route::get('/api/getAntenna', 'AntennaController@getAntenna');
	});
});

// Generic routes.. TODO
Route::any('/logout', 'GenericController@logout');

// ALL ROUTES SHOULD GO BEFORE THIS ONE!
Route::any('{all}', array('uses' => 'GenericController@defaultRoute'))->where('all', '.*');
