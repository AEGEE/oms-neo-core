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
//Route::any('/oauth/login', 'LoginController@loginUsingOauth'); TODO
//Route::any('/oauth/callback', 'LoginController@oAuthCallback'); TODO

Route::post('/api/login', 'LoginController@loginUsingCredentials');
//Route::get('/api/registration/fields', 'LoginController@getRegistrationFields'); TODO

//Route::get('/api/users/avatars/{avatar_id}', 'UserController@getUserAvatar'); TODO

// Core api routes..
Route::group(['middleware' => 'api'], function() {
    // Routes go in here..
    Route::put('/session', 'GenericController@noSessionTimeout');
    //Route::get('/api/notifications', 'GenericController@getNotifications'); TODO
    //Route::put('/api/notifications', 'GenericController@markNotificationsAsRead'); TODO

    // Personal routes..
    //Route::post('/api/users/{user_id}/avatars', 'UserController@uploadUserAvatar'); TODO

    // Antennae management..
    Route::group(['middleware' => 'checkAccess:antennae_management'], function() {
        Route::get('/api/bodies', 'BodyController@getBodies');
        Route::get('/api/bodies/{id}', 'BodyController@getBody')->where('id', '[0-9]+');
        Route::put('/api/bodies/{id}', 'BodyController@updateBody')->where('id', '[0-9]+');
        //Route::post('/api/bodies/', 'BodyController@createBody')->where('id', '[0-9]+'); TODO
    });

    // Roles..
    Route::group(['middleware' => 'checkAccess:roles'], function() {
        //TODO roles rework.
        //Route::get('/api/getRoles', 'RoleController@getRoles');
        //Route::get('/api/getModulePages', 'ModuleController@getModulePages');
        //Route::post('/api/saveRole', 'RoleController@saveRole');
        //Route::get('/api/getRole', 'RoleController@getRole');
    });

    // Users..
    Route::group(['middleware' => 'checkAccess:users'], function() {
        // GET - request
        Route::get('/api/users', 'UserController@getUsers');
        Route::get('/api/users/{id}', 'UserController@getUser')->where('id', '[0-9]+');
        //TODO allow seo-urls ('[a-zA-Z0-9_]+') -> laravel cannot assume the id anymore.

        // PUT - update
        // Route::put('/api/users/{id}', 'UserController@updateUser')->where('id', '[0-9]+'); TODO
        Route::put('/api/users/{id}/suspended', 'UserController@suspendUnsuspendAccount')->where('id', '[0-9]+');
        Route::put('/api/users/{id}/activated', 'UserController@activateUser')->where('id', '[0-9]+');
        Route::put('/api/users/{id}/impersonated', 'UserController@impersonateUser')->where('id', '[0-9]+');

        // POST - create
        //Route::post('/api/users', 'LoginController@createUser'); TODO
        //Route::post('/api/users/{user_id}/roles', 'UserController@addUserRoles'); TODO
        Route::post('/api/users/{user_id}/bodies', 'UserController@addBodyToUser')->where('user_id', '[0-9]+');

        // DELETE - remove
        //Route::delete('/api/roles/{role_id}', 'UserController@deleteRole'); TODO
    });

    // Settings..
    Route::group(['middleware' => 'checkAccess:settings'], function() {
        // Global..
        Route::get('/api/options', 'OptionController@getOptions');
        Route::get('/api/options/{option}', 'OptionController@getOption')->where('option', '[0-9]+');
        Route::put('/api/options/{option}', 'OptionController@updateOption')->where('option', '[0-9]+');

        // Menu..
        //Route::get('/api/menu', 'MenuController@getMenu'); TODO
        //Route::put('/api/menu', 'MenuController@saveMenu'); TODO
    });

    // Modules..
    Route::group(['middleware' => 'checkAccess:modules'], function() {
        //TODO all of the below.
        //Route::get('/api/modules', 'ModuleController@getModules');
        //Route::get('/api/subrid/modules', 'ModuleController@getModulePages'); // Duplicate route from /api/getModulePages but with other middleware
        //Route::post('/api/page/{id}/activate', 'ModuleController@activateDeactivatePage');
        //Route::post('/api/page/{id}/deactivate', 'ModuleController@activateDeactivatePage');
        //Route::post('/api/module/{id}/activate', 'ModuleController@activateDeactivateModule');
        //Route::post('/api/module/{id}/deactivate', 'ModuleController@activateDeactivateModule');
        //Route::get('/api/secret/shared', 'ModuleController@getSharedSecret');
        //Route::post('/api/secret/shared', 'ModuleController@generateNewSharedSecret');
    });

    Route::get('api/bodies/types', 'BodyTypeController@getBodyTypes');
    Route::get('api/bodies/types/{body_type}', 'BodyTypeController@getBodyType')->where('body_type', '[0-9]+');
    Route::get('api/addresses', 'AddressController@getAddresses');
    Route::get('api/addresses/{address}', 'AddressController@getAddress')->where('address', '[0-9]+');
    Route::get('api/countries', 'CountryController@getCountries');
    Route::get('api/countries/{country}', 'CountryController@getCountry')->where('country', '[0-9]+');
});

// Microservice routes..
//Route::post('/api/microservice/register', 'ModuleController@registerMicroservice'); TODO

// Microservice group..
Route::group(['middleware' => 'microServiceAuth'], function() {
    //TODO Should be GET method, but token should not be in URL.
    Route::post('/api/tokens/user', 'UserController@getUserByToken');
});

// Generic routes..
Route::any('/logout', 'GenericController@logout');

// ALL ROUTES SHOULD GO BEFORE THIS ONE!

Route::any('/api/{all}', function() { return response()->failure("Incorrect API URL");})->where('all', '.*');
Route::any('{all}', array('uses' => 'GenericController@defaultRoute'))->where('all', '.*');
