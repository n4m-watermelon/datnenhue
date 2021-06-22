<?php
// Admin dashboard
Route::get('/', [
    'as'         => 'dashboard.index',
    'uses'       => 'DashboardController@index',
    'permission' => false,
]);

Route::get('load', [
    'as'  => '',
    'uses'=> 'ReadFileXml@load',
    'permission' => false,
]);

// Cache tools
Route::get('cache/clearAll', [
    'as' => 'cache.clearall',
    'uses' => 'CacheToolController@clearAll',
    'permission' => false,
]);
Route::get('cache/clearApp', [
    'as' => 'cache.clearapp',
    'uses' => 'CacheToolController@clearApp',
    'permission' => false,
]);
Route::get('cache/clearView', [
    'as'   => 'cache.clearview',
    'uses' => 'CacheToolController@clearView',
    'permission' => false,
]);


/********************************************************************************/
Route::group(['prefix' => 'users'], function () {

    Route::get('make-super/{id}', [
        'as'         => 'users.make-super',
        'uses'       => 'UsersController@makeSuper',
        'permission' => 'superuser',
    ]);

    Route::get('remove-super/{id}', [
        'as'         => 'users.remove-super',
        'uses'       => 'UsersController@removeSuper',
        'permission' => 'superuser'
    ]);
    Route::get('profile/{id}', [
        'as'         => 'profile.edit',
        'uses'       => 'UsersController@profile',
        'permission' => false,
    ]);

    Route::put('profile/{id}', [
        'as'         => 'profile.update',
        'uses'       => 'UsersController@updateProfile',
        'permission' => false,
    ]);

    Route::post('change-password/{id}', [
        'as'         => 'users.change-password',
        'uses'       => 'UsersController@postChangePassword',
        'permission' => false,
    ]);
});

Route::resource('users', 'UsersController');

Route::get('dashboard', [
    'as'         => 'dashboard',
    'uses'       => 'DashboardController@index',
    'permission' => false,
]);
// Article
Route::resource('articles', 'ArticlesController');
//Pages
Route::resource('pages', 'PagesController');
// Contact item
Route::resource('contacts', 'ContactController');
// Role
Route::resource('roles', 'RolesController');

// Categories categories
Route::get('categories/spec/{component}', 'CategoriesController@indexSpec')->name('categories.indexSpec');

Route::get('categories/spec/{component}/create', 'CategoriesController@createSpec')->name('categories.createSpec');

Route::resource('categories', 'CategoriesController');
// Settings
Route::resource('settings', 'SettingsController');

Route::get('settings/{param}/reset', 'SettingsController@reset')->name('settings.reset');
// Shopping Products
Route::resource('slider_groups', 'SliderGroupController');
Route::resource('sliders', 'SliderController');
Route::resource('menu_groups', 'MenuGroupsController');
Route::resource('menu_items', 'MenuItemsController');
Route::get('menu_items/get_data/{id}', [
    'uses'       => 'MenuTypesController@getDataList',
    'permission' => false,
]);
Route::get('menu_items/get_menu_item/{id}', [
    'uses'       => 'MenuItemsController@getItemList',
    'permission' => false
]);
// ContentBlock component
Route::get('blocks/getDetailForm', [
    'uses'       => 'ContentBlocksController@getDetailForm',
    'permission' => false
])->name('blocks.getDetailForm');

Route::resource('blocks', 'ContentBlocksController');
Route::resource('projects', 'ProjectsController');
Route::post('estates/change/{id}/{value}/{field}', [
    'as'         => 'estates.change',
    'uses'       => 'EstatesController@change',
    'permission' => false
]);
Route::post('estates/ajax/categories-by-type', [
    'as'         => 'estates.ajax.type',
    'uses'       => 'EstatesController@getCategoriesByTypeId',
    'permission' => false
]);
Route::post('estates/ajax/district-by-province', [
    'as'   => 'estates.ajax.districts',
    'uses' => 'EstatesController@getDistrictByProvinceId',
    'permission' => false
]);
Route::post('estates/ajax/wards-street', [
    'as'   => 'estates.ajax.wards_streets',
    'uses' => 'EstatesController@getWardsAndStreets',
    'permission' => false
]);

Route::delete('estates/delImage/{id}', [
    'as'   => 'estates.delImage',
    'uses' => 'EstatesController@delImage',
    'permission' => false
]);

Route::get('estates/import',[
    'as'         => 'estates.import',
    'uses'       => 'EstatesController@getImportExcel',
    'permission' => false
]);

Route::post('estates/import', [
    'as'         => 'estates.postImport',
    'uses'       => 'EstatesController@postImportExcel',
    'permission' => false
]);

Route::get('estates/fix',[
    'as'         => 'estates.fix',
    'uses'       => 'EstatesController@fixImage',
    'permission' => false
]);

Route::get('estates/download-example', [
    'as'         => 'estates.downloadExample',
    'uses'       => 'EstatesController@downloadExample',
    'permission' => false
]);


Route::resource('estates', 'EstatesController');
Route::put('estates/{id}/titleImage', [
    'as'   => 'estates.titleImage',
    'uses' => 'EstatesController@titleImage'
]);
Route::resource('units', 'EstatesUnitsController');
Route::resource('directions', 'EstatesDirectionsController');
Route::resource('partners', 'PartnersController');
Route::resource('range_acreages', 'RangeAcreagesController');
Route::resource('range_prices', 'RangePricesController');
Route::resource('areas', 'AreasController');
// Compoment ui log error
/*
Route::get('logs', 'LogReaderController@index')->name('logs.index');
Route::put('logs/delete_all', 'LogReaderController@deleteAll')->name('logs.delete_all');
Route::get('log', 'LogActionController@getIndex')->name('logAction.index');*/

Route::resource('streets', 'StreetsController');
Route::resource('districts', 'DistrictsController');
Route::resource('wards', 'WardsController');
Route::resource('messages', 'MessagesController');
Route::resource('utilities', 'UtilitiesController');
Route::resource('tags', 'TagsController');
Route::delete('tags/{id}/detachAll', 'TagsController@detachAll')->name('tags.detachAll');
Route::resource('audit-logs','AuditHistoryController', ['names' => 'audit-log'])->only(['index', 'destroy']);
//Route::resource('taggables', 'TaggablesController');

Route::resource('boxes', 'BoxesController');
