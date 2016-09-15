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
// Route::post('/api/signup', 'LoginController@signup'); // Endpoint not available anymore, use recruitUser instead

Route::post('/api/recruitUser', 'RecrutementController@recruitUser');
Route::get('/api/checkCampaignExists', 'RecrutementController@checkCampaignExists');

Route::get('/api/getUserAvatar/{avatarId}', 'UserController@getUserAvatar');
// UI accessible only!
Route::get('/previewEmail/{templateName}', 'EmailController@previewEmail');

// Core api routes..
Route::group(['middleware' => 'api'], function() {
	// Routes go in here..
	Route::any('/noSessionTimeout', 'GenericController@noSessionTimeout');
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
		Route::post('/api/activateUser', 'UserController@activateUser');
		
		// Creates..
		Route::post('/api/setBoardPosition', 'UserController@setBoardPosition');
		Route::post('/api/addUserRoles', 'UserController@addUserRoles');
		Route::post('/api/addFeesToUser', 'UserController@addFeesToUser');
		Route::post('/api/addWorkingGroupToUser', 'UserController@addWorkingGroupToUser');

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

		// Mails..
		Route::get('/api/getEmailTemplates', 'EmailController@getEmailTemplates');
		Route::get('/api/getEmailTemplate', 'EmailController@getEmailTemplate');
		Route::post('/api/saveEmailTemplate', 'EmailController@saveEmailTemplate');
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

	// Recrutement campaigns
	Route::group(['middleware' => 'checkSpecialRoles:recrutement_campaigns,recruter'], function() {
		Route::get('/api/getRecrutementCampaigns', 'RecrutementController@getRecrutementCampaigns');
		Route::get('/api/checkLinkAvailability', 'RecrutementController@checkLinkAvailability');
		Route::post('/api/saveCampaign', 'RecrutementController@saveCampaign');
	});

	// Recruted users
	Route::group(['middleware' => 'checkSpecialRoles:recruted_users,recruter'], function() {
		Route::get('/api/getRecrutedUsers', 'RecrutementController@getRecrutedUsers');
		
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
