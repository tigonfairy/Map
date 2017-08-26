<?php

Route::get('language/{locale}', function ($locale) {
    $user = auth()->user();
    $user->lang = $locale;
    $user->save();
    Session::put('locale', $locale);
    return redirect()->back();
});

Auth::routes();

Route::group(['middleware' => ['auth','language'],
    'namespace' => 'Backend',
    'as' => 'Admin::'
],function() {

        Route::group([
            'prefix' => 'apis',
            'as' => 'Api::',
        ], function () {
            Route::group([
                'prefix' => 'area',
                'as' => 'area@',
            ], function () {
                Route::get('/get-list-areas', ['as' => 'getListAreas', 'uses' => 'ApiController@getListAreas']);
                Route::get('/get-list-address', ['as' => 'getListAddress', 'uses' => 'ApiController@getListAddress']);
            });

            Route::group([
                'prefix' => 'sale',
                'as' => 'sale@',
            ], function () {
                Route::get('/get-giam-sat-vung', ['as' => 'getGSV', 'uses' => 'ApiController@getGSV']);
                Route::get('/get-truong-vung', ['as' => 'getListTV', 'uses' => 'ApiController@getListTV']);
                Route::get('/get-list-agents', ['as' => 'getListAgents', 'uses' => 'ApiController@getListAgents']);
                Route::get('/get-giam-doc-vung', ['as' => 'getListGDV', 'uses' => 'ApiController@getListGDV']);
            });

            Route::group([
                'prefix' => 'saleAdmin',
                'as' => 'saleAdmin@',
            ], function () {
                Route::get('/get-list-saleAdmins', ['as' => 'getListSaleAdmins', 'uses' => 'ApiController@getListSaleAdmins']);
            });
        });

        Route::get('/', ['as' => 'dashboard', 'uses' => 'HomeController@dashboard']);
        Route::get('/home', ['as' => 'dashboard', 'uses' => 'HomeController@dashboard']);
        Route::post('/d', ['as' => 'chart', 'uses' => 'HomeController@chartDashboard']);

        Route::get('/admin', ['as' => 'dashboard', 'uses' => 'HomeController@dashboard']);
        Route::post('/', ['as' => 'dashboard', 'uses' => 'HomeController@dashboard']);
        Route::post('/admin', ['as' => 'dashboard', 'uses' => 'HomeController@dashboard']);

        Route::group([
            'prefix' => 'users',
            'as' => 'user@',
        ], function () {
            Route::get('/datatables', ['as' => 'datatables', 'uses' => 'UserController@getDatatables']);
            Route::post('/importExcel', ['as' => 'importExcel', 'uses' => 'UserController@importExcel']);
            Route::get('/', ['as' => 'index', 'uses' => 'UserController@index']);
            Route::get('/add', ['as' => 'add', 'uses' => 'UserController@add']);
            Route::post('/store', ['as' => 'store', 'uses' => 'UserController@store']);
            Route::get('/{id}/edit', ['as' => 'edit', 'uses' => 'UserController@edit']);
            Route::match(['put', 'patch'], '/{id}', ['as' => 'update', 'uses' => 'UserController@update']);
            Route::get('/{id}/delete', ['as' => 'delete', 'uses' => 'UserController@delete']);
            Route::post('/get-account-position', ['as' => 'getAccountPosition', 'uses' => 'UserController@getAccountPosition']);
            Route::get('/export',['as' => 'export', 'uses' => 'UserController@export']);
        });

        Route::group([
            'prefix' => 'notifications',
            'as' => 'notification@',
        ], function () {

            Route::get('/getNotification', ['as' => 'getNotification', 'uses' => 'NotificationController@getNotification']);
            Route::get('/getAll', ['as' => 'getAll', 'uses' => 'NotificationController@getAll']);
            Route::get('/detail-notification/{id}', ['as' => 'detailNotification', 'uses' => 'NotificationController@detailNotification']);
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
            Route::get('/datatables', ['as' => 'datatables', 'uses' => 'MapController@getDatatables']);
            Route::get('/', ['as' => 'index', 'uses' => 'MapController@index']);
            Route::get('/list-location',[ 'as' => 'listLocation','uses' => 'MapController@listLocation']);
            Route::get('/add-map', ['as' => 'addMap', 'uses' => 'MapController@addMap']);
            Route::post('/add-map', ['as' => 'addMapPost', 'uses' => 'MapController@addMapPost']);
            Route::get('/{id}/edit-map', ['as' => 'editMap', 'uses' => 'MapController@editMap']);
            Route::post('/{id}/edit-map', ['as' => 'editMapPost', 'uses' => 'MapController@editMapPost']);
            Route::get('/delete-map/{id}', ['as' => 'deleteMap', 'uses' => 'MapController@deleteMap']);

            Route::get('/list-map-user',[ 'as' => 'listMapUser','uses' => 'MapController@listMapUser']);
            Route::get('/list-agency',[ 'as' => 'listAgency','uses' => 'MapController@listAgency']);
            Route::get('/map-user-detail/{id}',[ 'as' => 'mapUserDetail','uses' => 'MapController@mapUserDetail']);
            Route::get('/map-user-delete/{id}',[ 'as' => 'mapUserDelete','uses' => 'MapController@mapUserDelete']);
            Route::get('/add-map-user', ['as' => 'addMapUser', 'uses' => 'MapController@addMapUser']);
            Route::post('/add-map-user', ['as' => 'addMapUserPost', 'uses' => 'MapController@addMapUserPost']);
            Route::get('/{id}/edit-map-user', ['as' => 'editMapUser', 'uses' => 'MapController@editMapUser']);
            Route::post('/{id}/edit-map-user', ['as' => 'editMapUser', 'uses' => 'MapController@editMapUserPost']);
            Route::post('/import-excel-agent', ['as' => 'importExcelAgent', 'uses' => 'MapController@importExcelAgent']);
            Route::get('/export-excel-agent', ['as' => 'exportAgency', 'uses' => 'MapController@exportAgency']);


            Route::get('/add-agency', ['as' => 'addAgency', 'uses' => 'MapController@addAgency']);
            Route::post('/add-agency', ['as' => 'addMapAgencyPost', 'uses' => 'MapController@addMapAgencyPost']);
            Route::get('/{id}/edit-agent', ['as' => 'editAgent', 'uses' => 'MapController@editAgent']);
            Route::post('/{id}/edit-agent', ['as' => 'editAgent', 'uses' => 'MapController@editAgentPost']);
            Route::get('/delete-agency/{id}', ['as' => 'agentDelete', 'uses' => 'MapController@agentDelete']);

            Route::get('/add-data-agency', ['as' => 'addDataAgency', 'uses' => 'MapController@addDataAgency']);
            Route::post('/add-data-agency', ['as' => 'addDataAgencyPost', 'uses' => 'MapController@addDataAgencyPost']);
            Route::get('/agent-detail/{id}',[ 'as' => 'agentDetail','uses' => 'MapController@agentDetail']);

            Route::get('/search', ['as' => 'search', 'uses' => 'MapController@search']);
            Route::get('/data-search', ['as' => 'dataSearch', 'uses' => 'MapController@dataSearch']);
        });


        Route::group([
            'prefix' => 'saleAgents',
            'as' => 'saleAgent@',
        ], function () {
            Route::get('/datatables', ['as' => 'datatables', 'uses' => 'SaleAgentController@getDatatables']);
            Route::get('/', ['as' => 'index', 'uses' => 'SaleAgentController@index']);
            Route::get('/add', ['as' => 'add', 'uses' => 'SaleAgentController@add']);
            Route::post('/store', ['as' => 'store', 'uses' => 'SaleAgentController@store']);
            Route::get('/{agentId}/{month}/edit', ['as' => 'edit', 'uses' => 'SaleAgentController@edit']);
            Route::match(['put', 'patch'], '/{id}', ['as' => 'update', 'uses' => 'SaleAgentController@update']);
            Route::get('/{agentId}/{month}/delete', ['as' => 'delete', 'uses' => 'SaleAgentController@delete']);

            Route::post('/import-excel-data-agent', ['as' => 'importExcelDataAgent', 'uses' => 'SaleAgentController@importExcelDataAgent']);
            Route::post('/export-excel-data-agent', ['as' => 'exportExcelDataAgent', 'uses' => 'SaleAgentController@exportExcelDataAgent']);
            Route::post('/export-excel-tien-do', ['as' => 'exportTienDo', 'uses' => 'SaleAgentController@exportTienDo']);

        });

        Route::group([
            'prefix' => 'products',
            'as' => 'product@',
        ], function () {
            Route::get('/datatables', ['as' => 'datatables', 'uses' => 'ProductController@getDatatables']);
            Route::post('/importExcel', ['as' => 'importExcel', 'uses' => 'ProductController@importExcel']);
            Route::get('/', ['as' => 'index', 'uses' => 'ProductController@index']);
            Route::get('/add', ['as' => 'add', 'uses' => 'ProductController@add']);
            Route::post('/store', ['as' => 'store', 'uses' => 'ProductController@store']);
            Route::get('/{id}/edit', ['as' => 'edit', 'uses' => 'ProductController@edit']);
            Route::match(['put', 'patch'], '/{id}', ['as' => 'update', 'uses' => 'ProductController@update']);
            Route::get('/{id}/delete', ['as' => 'delete', 'uses' => 'ProductController@delete']);
            Route::get('/export',['as' => 'export', 'uses' => 'ProductController@export']);


        });

    Route::group([
        'prefix' => 'group_products',
        'as' => 'group_product@',
    ], function () {
        Route::get('/datatables', ['as' => 'datatables', 'uses' => 'GroupProductController@getDatatables']);
        Route::get('/', ['as' => 'index', 'uses' => 'GroupProductController@index']);
        Route::get('/add', ['as' => 'add', 'uses' => 'GroupProductController@add']);
        Route::post('/store', ['as' => 'store', 'uses' => 'GroupProductController@store']);
        Route::get('/{id}/edit', ['as' => 'edit', 'uses' => 'GroupProductController@edit']);
        Route::match(['put', 'patch'], '/{id}', ['as' => 'update', 'uses' => 'GroupProductController@update']);
        Route::get('/{id}/delete', ['as' => 'delete', 'uses' => 'GroupProductController@delete']);
    });

        Route::group([
            'prefix' => 'logs',
            'as' => 'log@',
        ], function () {
            Route::get('/datatables', ['as' => 'datatables', 'uses' => 'HistoryController@getDatatables']);
            Route::get('/', ['as' => 'index', 'uses' => 'HistoryController@index']);
        });

        Route::group([
            'prefix' => 'configs',
            'as' => 'config@',
        ], function () {
            Route::get('/', ['as' => 'index', 'uses' => 'ConfigController@index']);
            Route::post('/store', ['as' => 'store', 'uses' => 'ConfigController@store']);

            Route::get('/global-config', ['as' => 'globalConfig', 'uses' => 'ConfigController@globalConfig']);
            Route::post('/global-config', ['as' => 'globalConfig', 'uses' => 'ConfigController@postGlobalConfig']);
        });

});
?>