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

	Route::get('/api/getUserProfile', 'UserController@getUserProfile');
	Route::get('/api/getDashboardData', 'UserController@getDashboardData');

	// Personal routes..
	Route::post('/api/changeEmail', 'UserController@changeEmail');
	Route::post('/api/changePassword', 'UserController@changePassword');
	Route::post('/api/editBio', 'UserController@editBio');
	Route::post('/api/uploadUserAvatar', 'UserController@uploadUserAvatar');

	// Antennae management..
	Route::group(['middleware' => 'checkAccess:antennae_management'], function() {
		Route::get('/api/getAntennae', 'AntennaController@getAntennae');
		Route::post('/api/saveAntenna', 'AntennaController@saveAntenna');
		Route::get('/api/getAntenna', 'AntennaController@getAntenna');
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
		Route::get('/api/getUser', 'UserController@getUser');
		Route::post('/api/activateUser', 'UserController@activateUser');

		// Creates..
		Route::post('/api/setBoardPosition', 'UserController@setBoardPosition');
		Route::post('/api/addUserRoles', 'UserController@addUserRoles');
		Route::post('/api/addFeesToUser', 'UserController@addFeesToUser');
		Route::post('/api/addWorkingGroupToUser', 'UserController@addWorkingGroupToUser');
		Route::post('/api/createUser', 'LoginController@createUser');

		// Deletes..
		Route::post('/api/deleteFee', 'UserController@deleteFee');
		Route::post('/api/deleteRole', 'UserController@deleteRole');
		Route::post('/api/deleteMembership', 'UserController@deleteMembership');
		Route::post('/api/deleteWorkGroup', 'UserController@deleteWorkGroup');

		// Suspensions and others..
		Route::post('/api/suspendAccount', 'UserController@suspendAccount');
		Route::post('/api/unsuspendAccount', 'UserController@unsuspendAccount');
		Route::post('/api/impersonateUser', 'UserController@impersonateUser');

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
