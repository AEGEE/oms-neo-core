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
Route::get('/api/registration/fields', 'LoginController@getRegistrationFields');

Route::get('/api/users/avatars/{avatar_id}', 'UserController@getUserAvatar');

// Core api routes..
Route::group(['middleware' => 'api'], function() {
    // Routes go in here..
    Route::put('/session', 'GenericController@noSessionTimeout');
    Route::get('/api/notifications', 'GenericController@getNotifications');
    Route::put('/api/notifications', 'GenericController@markNotificationsAsRead');

    // Personal routes..
    Route::post('/api/users/{user_id}/avatars', 'UserController@uploadUserAvatar');

    // Antennae management..
    Route::group(['middleware' => 'checkAccess:antennae_management'], function() {
        Route::get('/api/bodies', 'BodyController@getBodies');
        Route::put('/api/bodies/{id}', 'BodyController@saveBody')->where('id', '[0-9]+');
        Route::get('/api/bodies/{id}', 'BodyController@getBody')->where('id', '[0-9]+');
        //TODO Route::post('/api/bodies/', 'BodyController@createBody')->where('id', '[0-9]+');
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
        // GET - request
        Route::get('/api/users', 'UserController@getUsers');
        Route::get('/api/users/{id}', 'UserController@getUser')->where('id', '[0-9]+');
        //TODO allow seo-urls ('[a-zA-Z0-9_]+') -> laravel cannot assume the id anymore.

        // PUT - update
        Route::put('/api/users/{id}', 'UserController@saveUser')->where('id', '[0-9]+');
        Route::put('/api/users/{id}/suspendended', 'UserController@suspendUnsuspendAccount')->where('id', '[0-9]+');
        Route::put('/api/users/{id}/activated', 'UserController@activateUser')->where('id', '[0-9]+');
        Route::put('/api/users/{id}/impersonated', 'UserController@impersonateUser')->where('id', '[0-9]+');

        // POST - create
        Route::post('/api/users', 'LoginController@createUser');
        Route::post('/api/users/{user_id}/roles', 'UserController@addUserRoles');
        Route::post('/api/users/{user_id}/bodies/{body_id}', 'UserController@addBodyToUser')->where('user_id', '[0-9]+')->where('body_id', '[0-9]+');

        // DELETE - remove
        //TODO rework with roles, currently requires UserRole id..
        Route::delete('/api/roles/{role_id}', 'UserController@deleteRole');
    });

    // Settings..
    Route::group(['middleware' => 'checkAccess:settings'], function() {
        // Global..
        Route::get('/api/options', 'OptionController@getOptions');
        Route::get('/api/options/{id}', 'OptionController@getOption');
        Route::put('/api/options', 'OptionController@saveOption');

        // Menu..
        Route::put('/api/menu', 'MenuController@saveMenu');
        Route::get('/api/menu', 'MenuController@getMenu');
    });

    // Modules..
    Route::group(['middleware' => 'checkAccess:modules'], function() {
        Route::get('/api/modules', 'ModuleController@getModules');
        Route::get('/api/subrid/modules', 'ModuleController@getModulePages'); // Duplicate route from /api/getModulePages but with other middleware
        Route::post('/api/page/{id}/activate', 'ModuleController@activateDeactivatePage');
        Route::post('/api/page/{id}/deactivate', 'ModuleController@activateDeactivatePage');
        Route::post('/api/module/{id}/activate', 'ModuleController@activateDeactivateModule');
        Route::post('/api/module/{id}/deactivate', 'ModuleController@activateDeactivateModule');
        Route::get('/api/secret/shared', 'ModuleController@getSharedSecret');
        Route::post('/api/secret/shared', 'ModuleController@generateNewSharedSecret');
    });

    Route::get('api/bodies/types', 'BodyTypeController@getBodyTypes');
    Route::get('api/bodies/types/{body_type}', 'BodyTypeController@getBodyType')->where('body_type', '[0-9]+');
    Route::get('api/addresses', 'AddressController@getAddresses');
    Route::get('api/addresses/{address}', 'AddressController@getAddress')->where('address', '[0-9]+');
    Route::get('api/countries', 'CountryController@getCountries');
    Route::get('api/countries/{country}', 'CountryController@getCountry')->where('country', '[0-9]+');
});

// Microservice routes..
Route::post('/api/microservice/register', 'ModuleController@registerMicroservice');

// Microservice group..
Route::group(['middleware' => 'microServiceAuth'], function() {
    Route::get('/api/tokens/user', 'UserController@getUserByToken');
});

// Generic routes.. TODO
Route::any('/logout', 'GenericController@logout');

// ALL ROUTES SHOULD GO BEFORE THIS ONE!

Route::any('/api/{all}', function() { return response()->failure("Incorrect API URL");})->where('all', '.*');
Route::any('{all}', array('uses' => 'GenericController@defaultRoute'))->where('all', '.*');
