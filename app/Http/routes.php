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
	Route::any('/noSessionTimeout', 'GenericController@noSessionTimeout');

	// Antennae management..
	Route::group(['middleware' => 'checkAccess:antennae_management'], function() {
		Route::get('/api/getAntennae', 'AntennaController@getAntennae');
		Route::post('/api/saveAntenna', 'AntennaController@saveAntenna');
		Route::get('/api/getAntenna', 'AntennaController@getAntenna');
	});

	Route::group(['middleware' => 'checkAccess:working_groups'], function() {
		Route::get('/api/getWorkingGroups', 'WorkingGroupController@getWorkingGroups');
		Route::post('/api/saveWorkGroup', 'WorkingGroupController@saveWorkGroup');
		Route::get('/api/getWorkGroup', 'WorkingGroupController@getWorkGroup');
	});

	Route::group(['middleware' => 'checkAccess:departments'], function() {
		Route::get('/api/getDepartments', 'DepartmentController@getDepartments');
		Route::post('/api/saveDepartment', 'DepartmentController@saveDepartment');
		Route::get('/api/getDepartment', 'DepartmentController@getDepartment');
	});
});

// Generic routes.. TODO
Route::any('/logout', 'GenericController@logout');

// ALL ROUTES SHOULD GO BEFORE THIS ONE!
Route::any('{all}', array('uses' => 'GenericController@defaultRoute'))->where('all', '.*');
