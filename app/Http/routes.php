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
Route::get('/api/getRegistrationFields', 'LoginController@getRegistrationFields');
Route::post('/api/signup', 'LoginController@signup');

// UI accessible only!
Route::get('/previewEmail/{templateName}', 'EmailController@previewEmail');

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

	// Working groups..
	Route::group(['middleware' => 'checkAccess:working_groups'], function() {
		Route::get('/api/getWorkingGroups', 'WorkingGroupController@getWorkingGroups');
		Route::post('/api/saveWorkGroup', 'WorkingGroupController@saveWorkGroup');
		Route::get('/api/getWorkGroup', 'WorkingGroupController@getWorkGroup');
	});

	// Departments..
	Route::group(['middleware' => 'checkAccess:departments'], function() {
		Route::get('/api/getDepartments', 'DepartmentController@getDepartments');
		Route::post('/api/saveDepartment', 'DepartmentController@saveDepartment');
		Route::get('/api/getDepartment', 'DepartmentController@getDepartment');
	});

	// Fees management..
	Route::group(['middleware' => 'checkAccess:fees_management'], function() {
		Route::get('/api/getFees', 'FeeController@getFees');
		Route::post('/api/saveFee', 'FeeController@saveFee');
		Route::get('/api/getFee', 'FeeController@getFee');
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
	});

	// Settings..
	Route::group(['middleware' => 'checkAccess:settings'], function() {
		// Global..
		Route::get('/api/getOptions', 'OptionController@getOptions');
		Route::get('/api/getOption', 'OptionController@getOption');
		Route::post('/api/saveOption', 'OptionController@saveOption');

		// Mails..
		Route::get('/api/getEmailTemplates', 'EmailController@getEmailTemplates');
		Route::get('/api/getEmailTemplate', 'EmailController@getEmailTemplate');
		Route::post('/api/saveEmailTemplate', 'EmailController@saveEmailTemplate');
	});
});

// Generic routes.. TODO
Route::any('/logout', 'GenericController@logout');

// ALL ROUTES SHOULD GO BEFORE THIS ONE!
Route::any('{all}', array('uses' => 'GenericController@defaultRoute'))->where('all', '.*');
