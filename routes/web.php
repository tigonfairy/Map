<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Auth::routes();
Route::group(['middleware' => 'auth',
    'namespace' => 'Backend',
    'as' => 'Admin::'
],function() {

    Route::get('/', function() {
        return view('admin');
    });
    Route::get('admin', function() {
        return view('admin');
    });

    Route::group([
        'prefix' => 'users',
        'as' => 'user@',
    ], function () {
        Route::get('/', ['as' => 'index', 'uses' => 'UserController@index']);
        Route::get('/add', ['as' => 'add', 'uses' => 'UserController@add']);
        Route::post('/store', ['as' => 'store', 'uses' => 'UserController@store']);
        Route::get('/{id}/edit', ['as' => 'edit', 'uses' => 'UserController@edit']);
        Route::match(['put', 'patch'], '/{id}', ['as' => 'update', 'uses' => 'UserController@update']);
        Route::get('/{id}/delete', ['as' => 'delete', 'uses' => 'UserController@delete']);

    });
    Route::group([
        'prefix' => 'permissions',
        'as' => 'permission@',
    ], function () {
        Route::get('/', ['as' => 'index', 'uses' => 'PermissionController@index']);
        Route::get('/add', ['as' => 'add', 'uses' => 'PermissionController@add']);
        Route::post('/store', ['as' => 'store', 'uses' => 'PermissionController@store']);
        Route::get('/{id}/edit', ['as' => 'edit', 'uses' => 'PermissionController@edit']);
        Route::match(['put', 'patch'], '/{id}', ['as' => 'update', 'uses' => 'PermissionController@update']);
        Route::get('/{id}/delete', ['as' => 'delete', 'uses' => 'PermissionController@delete']);

    });
    Route::group([
        'prefix' => 'roles',
        'as' => 'role@',
    ], function () {
        Route::get('/', ['as' => 'index', 'uses' => 'RoleController@index']);
        Route::get('/add', ['as' => 'add', 'uses' => 'RoleController@add']);
        Route::post('/store', ['as' => 'store', 'uses' => 'RoleController@store']);
        Route::get('/{id}/edit', ['as' => 'edit', 'uses' => 'RoleController@edit']);
        Route::match(['put', 'patch'], '/{id}', ['as' => 'update', 'uses' => 'RoleController@update']);
        Route::get('/{id}/delete', ['as' => 'delete', 'uses' => 'RoleController@delete']);
    });

    Route::group([
        'prefix' => 'maps',
        'as' => 'map@',
    ], function () {
        Route::get('/', ['as' => 'index', 'uses' => 'MapController@index']);
        Route::post('province/districts',['as' => 'district', 'uses' => 'MapController@getDistricts']);
        Route::post('province/district/coordinates',['as' => 'coordinates', 'uses' => 'MapController@getCoordinates']);
    });

});