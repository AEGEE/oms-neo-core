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

// Login routes..
Route::group(['middleware' => ['login:credentials', 'returnErrors']], function() {
    Route::post('/api/login', 'LoginController@loginUsingCredentials');
});

Route::group(['middleware' => 'login:oauth'], function() {
    Route::any('/oauth/login', 'LoginController@loginUsingOauth');
    Route::any('/oauth/callback', 'LoginController@oAuthCallback');
});

//TODO Prevent spam, let user have guest token.
Route::post('/api/users', 'UserController@createUser');

// Core api routes..
Route::group(['middleware' => 'api'], function() {

    // Routes go in here..
    Route::put('/session', 'GenericController@noSessionTimeout');
    Route::post('/api/tokens/user', 'UserController@getUserByToken'); // TODO restrict access to this?
    //Route::get('/api/notifications', 'GenericController@getNotifications'); TODO
    //Route::put('/api/notifications', 'GenericController@markNotificationsAsRead'); TODO

    // Personal routes..
    //Route::post('/api/users/{user_id}/avatars', 'UserController@uploadUserAvatar'); TODO

    // Antennae management..
    Route::get('/api/bodies', 'BodyController@getBodies');
    Route::get('/api/bodies/{body_id}', 'BodyController@getBody')->where('body_id', '[0-9]+');
    Route::put('/api/bodies/{body_id}', 'BodyController@updateBody')->where('body_id', '[0-9]+');
    Route::post('/api/bodies/', 'BodyController@createBody');

    // Users..
    Route::get('/api/users', 'UserController@getUsers');
    Route::put('/api/users/{user_id}', 'UserController@updateUser')->where('user_id', '[a-zA-Z0-9_]+');
    //Route::get('/api/users/avatars/{avatar_id}', 'UserController@getUserAvatar'); TODO
    Route::group(['middleware' => 'seoURL:user_id'], function() {
        Route::get('/api/users/{user_id}', 'UserController@getUser')->where('user_id', '[a-zA-Z0-9_]+');
        Route::get('/api/users/{user_id}/bodies', 'UserController@getBodies')->where('user_id', '[a-zA-Z0-9_]+');

        // Route::put('/api/users/{id}', 'UserController@updateUser')->where('id', '[0-9]+'); TODO
        Route::put('/api/users/{user_id}/suspend', 'UserController@suspendUnsuspendAccount')->where('user_id', '[a-zA-Z0-9_]+');
        Route::put('/api/users/{user_id}/activate', 'UserController@activateUser')->where('user_id', '[a-zA-Z0-9_]+');
        Route::put('/api/users/{user_id}/impersonate', 'UserController@impersonateUser')->where('user_id', '[a-zA-Z0-9_]+');

        //Route::post('/api/users', 'LoginController@createUser'); TODO
        //Route::post('/api/users/{user_id}/roles', 'UserController@addUserRoles'); TODO
        Route::post('/api/users/{user_id}/bodies', 'UserController@addBodyToUser')->where('user_id', '[a-zA-Z0-9_]+');
    });

    // DELETE - remove
    //Route::delete('/api/roles/{role_id}', 'UserController@deleteRole'); TODO

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
    //TODO all of the below.
    Route::get('/api/modules', 'ModuleController@getModules');
    Route::get('/api/subrid/modules', 'ModuleController@getModulePages'); // Duplicate route from /api/getModulePages but with other middleware
    Route::post('/api/page/{id}/activate', 'ModuleController@activateDeactivatePage');
    Route::post('/api/page/{id}/deactivate', 'ModuleController@activateDeactivatePage');
    Route::post('/api/module/{id}/activate', 'ModuleController@activateDeactivateModule');
    Route::post('/api/module/{id}/deactivate', 'ModuleController@activateDeactivateModule');
    Route::get('/api/secret/shared', 'ModuleController@getSharedSecret');
    Route::post('/api/secret/shared', 'ModuleController@generateNewSharedSecret');

    Route::get('api/bodies/types', 'BodyTypeController@getBodyTypes');
    Route::get('api/bodies/types/{body_type_id}', 'BodyTypeController@getBodyType')->where('body_type_id', '[0-9]+');
    Route::put('api/bodies/types/{body_type_id}', 'BodyTypeController@updateBodyType')->where('body_type_id', '[0-9]+');
    Route::post('api/bodies/types', 'BodyTypeController@createBodyType');

    Route::get('api/addresses', 'AddressController@getAddresses');
    Route::get('api/addresses/{address_id}', 'AddressController@getAddress')->where('address_id', '[0-9]+');
    Route::put('api/addresses/{address_id}', 'AddressController@updateAddress')->where('address_id', '[0-9]+');
    Route::post('api/addresses', 'AddressController@createAddress');

    Route::get('api/countries', 'CountryController@getCountries');
    Route::get('api/countries/{country_id}', 'CountryController@getCountry')->where('country_id', '[0-9]+');
    Route::put('api/countries/{country_id}', 'CountryController@updateCountry')->where('country_id', '[0-9]+');
    Route::post('api/countries', 'CountryController@createCountry');

    Route::get('api/circles/', 'CircleController@getCircles');
    Route::get('api/circles/{circle_id}', 'CircleController@getCircle')->where('circle_id', '[0-9]+');
    Route::get('api/circles/{circle_id}/children', 'CircleController@getCircleChildren')->where('circle_id', '[0-9]+');
    Route::get('api/circles/{circle_id}/parent', 'CircleController@getCircleParents')->where('circle_id', '[0-9]+');
    Route::get('api/circles/{circle_id}/members', 'CircleController@getCircleMembers')->where('circle_id', '[0-9]+');

    Route::get('api/bodies/{body_id}/circles', 'CircleController@getCirclesOfBody')->where('body_id', '[0-9]+');
    Route::group(['middleware' => 'seoURL:user_id'], function() {
        Route::get('api/users/{user_id}/circles', 'CircleController@getCirclesOfUser')->where('user_id', '[a-zA-Z0-9_]+');
    });
});

// Microservice routes..
Route::post('/api/microservice/register', 'ModuleController@registerMicroservice');


// Generic routes..
Route::any('/logout', 'GenericController@logout');

// ALL ROUTES SHOULD GO BEFORE THIS ONE!
Route::any('/api/{all}', function() { return response()->failure("Incorrect API URL");})->where('all', '.*');
Route::any('{all}', array('uses' => 'GenericController@defaultRoute'))->where('all', '.*');
