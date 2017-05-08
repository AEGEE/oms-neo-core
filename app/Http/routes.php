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
Route::any('/oauth/login', 'LoginController@loginUsingOauth');
Route::any('/oauth/callback', 'LoginController@oAuthCallback');

Route::post('/api/login', 'LoginController@loginUsingCredentials');
Route::get('/api/getRegistrationFields', 'LoginController@getRegistrationFields');
// Route::post('/api/signup', 'LoginController@signup'); // Endpoint not available anymore, use recruitUser instead

Route::get('/api/getUserAvatar/{avatarId}', 'UserController@getUserAvatar');

// Core api routes..
Route::group(['middleware' => 'api'], function() {
	// Routes go in here..
	Route::any('/noSessionTimeout', 'GenericController@noSessionTimeout');
	Route::any('/api/getNotifications', 'GenericController@getNotifications');
	Route::any('/api/markNotificationsAsRead', 'GenericController@markNotificationsAsRead');

	// Personal routes..
	Route::post('/api/uploadUserAvatar', 'UserController@uploadUserAvatar');

	// Antennae management..
	Route::group(['middleware' => 'checkAccess:antennae_management'], function() {
		Route::get('/api/getBodies', 'BodyController@getBodies');
		Route::post('/api/saveBody/{id}', 'BodyController@saveBody')->where('id', '[0-9]+');
		Route::get('/api/getBody/{id}', 'BodyController@getBody')->where('id', '[0-9]+');
        //TODO /api/createBody
	});

	// Roles..
	Route::group(['middleware' => 'checkAccess:roles'], function() {
		Route::get('/api/getRoles', 'RoleController@getRoles');
		Route::get('/api/getModulePages', 'ModuleController@getModulePages');
		Route::post('/api/saveRole', 'RoleController@saveRole');
		Route::get('/api/getRole', 'RoleController@getRole');
	});

	// Users..
	Route::group(['middleware' => 'checkAccess:users'], function() {
		Route::get('/api/getUsers', 'UserController@getUsers');
		Route::get('/api/getUser/{id}', 'UserController@getUser')->where('id', '[0-9]+');

        // Updates..
		Route::get('/api/saveUser/{id}', 'UserController@saveUser')->where('id', '[0-9]+');
		Route::post('/api/activateUser/{id}', 'UserController@activateUser')->where('id', '[0-9]+');
        Route::post('api/user/{user_id}/join/body/{body_id}')->where('user_id', '[0-9]+')->where('body_id', '[0-9]+');;

		// Creates..
		Route::post('/api/createUser', 'LoginController@createUser');
		Route::post('/api/createUserRoles', 'UserController@addUserRoles');

		// Deletes..
		Route::post('/api/deleteRole', 'UserController@deleteRole');

		// Suspensions and others..
		Route::post('/api/suspendAccount/{id}', 'UserController@suspendAccount')->where('id', '[0-9]+');
		Route::post('/api/unsuspendAccount/{id}', 'UserController@unsuspendAccount')->where('id', '[0-9]+');
		Route::post('/api/impersonateUser/{id}', 'UserController@impersonateUser')->where('id', '[0-9]+');

	});

	// Settings..
	Route::group(['middleware' => 'checkAccess:settings'], function() {
		// Global..
		Route::get('/api/getOptions', 'OptionController@getOptions');
		Route::get('/api/getOption', 'OptionController@getOption');
		Route::post('/api/saveOption', 'OptionController@saveOption');

		// Menu..
		Route::post('/api/saveMenu', 'MenuController@saveMenu');
		Route::get('/api/getMenu', 'MenuController@getMenu');
	});

	// Modules..
	Route::group(['middleware' => 'checkAccess:modules'], function() {
		Route::get('/api/getModules', 'ModuleController@getModules');
		Route::get('/api/getModulePagesForSubgrid', 'ModuleController@getModulePages'); // Duplicate route from /api/getModulePages but with other middleware
		Route::post('/api/activateDeactivatePage', 'ModuleController@activateDeactivatePage');
		Route::post('/api/activateDeactivateModule', 'ModuleController@activateDeactivateModule');
		Route::get('/api/getSharedSecret', 'ModuleController@getSharedSecret');
		Route::post('/api/generateNewSharedSecret', 'ModuleController@generateNewSharedSecret');
	});
});

// Microservice routes..
Route::post('/api/registerMicroservice', 'ModuleController@registerMicroservice');

// Microservice group..
Route::group(['middleware' => 'microServiceAuth'], function() {
	Route::post('/api/getUserByToken', 'UserController@getUserByToken');
});

// Generic routes.. TODO
Route::any('/logout', 'GenericController@logout');

// ALL ROUTES SHOULD GO BEFORE THIS ONE!
Route::any('{all}', array('uses' => 'GenericController@defaultRoute'))->where('all', '.*');
