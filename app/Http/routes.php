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

Route::post('/api/recruitUser', 'RecruitmentController@recruitUser');
Route::get('/api/checkCampaignExists', 'RecruitmentController@checkCampaignExists');

Route::get('/api/getMemberAvatar/{avatarId}', 'MemberController@getMemberAvatar');
// UI accessible only!
Route::get('/previewEmail/{templateName}', 'EmailController@previewEmail');

// Core api routes..
Route::group(['middleware' => 'api'], function() {
	// Routes go in here..
	Route::any('/noSessionTimeout', 'GenericController@noSessionTimeout');
	Route::any('/api/getNotifications', 'GenericController@getNotifications');
	Route::any('/api/markNotificationsAsRead', 'GenericController@markNotificationsAsRead');

	Route::get('/api/getMemberProfile', 'MemberController@getMemberProfile');
	Route::get('/api/getDashboardData', 'MemberController@getDashboardData');

	Route::get('/api/getNews', 'NewsController@getNews');
	Route::get('/api/getNewsById', 'NewsController@getNewsById');

	// Personal routes..
	Route::post('/api/changeEmail', 'MemberController@changeEmail');
	Route::post('/api/changePassword', 'MemberController@changePassword');
	Route::post('/api/editBio', 'MemberController@editBio');
	Route::post('/api/uploadUserAvatar', 'MemberController@uploadUserAvatar');

	// Body management..
	Route::group(['middleware' => 'checkAccess:body_management'], function() {
		Route::get('/api/getBodies', 'BodyController@getBodies');
		Route::post('/api/saveBody', 'BodyController@saveBody');
		Route::get('/api/getBody/{body}', 'BodyController@getBody');
	});

	// Roles..
	Route::group(['middleware' => 'checkAccess:roles'], function() {
		Route::get('/api/getRoles', 'RoleController@getRoles');
		Route::get('/api/getModulePages', 'ModuleController@getModulePages');
		Route::post('/api/saveRole', 'RoleController@saveRole');
		Route::get('/api/getRole', 'RoleController@getRole');
	});

	// Users..
	Route::group(['middleware' => 'checkAccess:members'], function() {
		Route::get('/api/getMembers', 'MemberController@getMembers');
		Route::get('/api/getMember/{member}', 'MemberController@getMember');
		Route::post('/api/activateUser', 'MemberController@activateUser');

		// Creates..
		Route::post('/api/setBoardPosition', 'MemberController@setBoardPosition');
		Route::post('/api/addMemberRoles', 'MemberController@addMemberRoles');
		Route::post('/api/addFeesToUser', 'MemberController@addFeesToUser');
		Route::post('/api/addWorkingGroupToUser', 'MemberController@addWorkingGroupToUser');
		Route::post('/api/createUser', 'LoginController@createUser');

		// Deletes..
		Route::post('/api/deleteFee', 'MemberController@deleteFee');
		Route::post('/api/deleteRole', 'MemberController@deleteRole');
		Route::post('/api/deleteMembership', 'MemberController@deleteMembership');
		Route::post('/api/deleteWorkGroup', 'MemberController@deleteWorkGroup');

		// Suspensions and others..
		Route::post('/api/suspendAccount', 'MemberController@suspendAccount');
		Route::post('/api/unsuspendAccount', 'MemberController@unsuspendAccount');
		Route::post('/api/impersonateUser', 'MemberController@impersonateUser');

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

	// Recruitment campaigns
	Route::group(['middleware' => 'checkSpecialRoles:recruitment_campaigns,recruiter'], function() {
		Route::get('/api/getRecruitmentCampaigns', 'RecruitmentController@getRecruitmentCampaigns');
		Route::get('/api/checkLinkAvailability', 'RecruitmentController@checkLinkAvailability');
		Route::post('/api/saveCampaign', 'RecruitmentController@saveCampaign');
	});

	// Recruited users
	Route::group(['middleware' => 'checkSpecialRoles:recruited_users,recruiter'], function() {
		Route::get('/api/getRecruitedUsers', 'RecruitmentController@getRecruitedUsers');
		Route::get('/api/getMemberDetails', 'RecruitmentController@getMemberDetails');
		Route::post('/api/addComment', 'RecruitmentController@addComment');
		Route::post('/api/changeStatus', 'RecruitmentController@changeStatus');
		Route::post('/api/activateUserRecruited', 'RecruitmentController@activateUserRecruited');

	});

	// News
	Route::group(['middleware' => 'checkSpecialRoles:null,announcer'], function() {
		Route::post('/api/saveNews', 'NewsController@saveNews');
		Route::post('/api/deleteNews', 'NewsController@deleteNews');

	});
});

// Microservice routes..
Route::post('/api/registerMicroservice', 'ModuleController@registerMicroservice');

// Microservice group..
Route::group(['middleware' => 'microServiceAuth'], function() {
	Route::post('/api/getMemberByToken', 'MemberController@getMemberByToken');
	Route::post('/api/getMemberById', 'MemberController@getMemberById');
});

// Generic routes.. TODO
Route::any('/logout', 'GenericController@logout');
Log::debug("No route found, redirecting to default.");
// ALL ROUTES SHOULD GO BEFORE THIS ONE!
Route::any('{all}', array('uses' => 'GenericController@defaultRoute'))->where('all', '.*');
