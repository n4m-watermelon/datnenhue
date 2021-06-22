<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', [
    'as' => 'home.index',
    'uses' => 'HomeController@index'
]);
Route::get('/test-1', [
    'as' => 'home.test',
    'uses' => 'HomeController@test'
]);
Route::get('manipulate/{width}/{height}/{path}', [
    'as' => 'image.manipulate',
    'uses' => 'ImageManipulateController@manipulate'
])->where([
    'width' => '[\d]+',
    'height' => '[\d]+',
    'path' => '(.*)?'
]);

// Path parser
Route::get('{path_alias}.html', [
    'as'   => 'paths.parse',
    'uses' => 'PathsController@parse'
])->where([
    'path_alias' => '(.*)?',
    'id' => '[\d]+'
]);
/*
Route::group([
    'prefix' => config('cms.general.admin_dir'),
    'namespace' => 'Admin',
    'as' => 'admin::'
], function () {
    Route::group(['middleware' => 'guest'], function () {

        Route::get('login', [
            'as' => 'access.login',
            'uses' => 'Auth\LoginController@showLoginForm'
        ]);

        Route::post('login', [
            'as' => 'access.login',
            'uses' => 'Auth\LoginController@login'
        ]);

        Route::get('password/reset', [
            'as' => 'access.password.request',
            'uses' => 'Auth\ForgotPasswordController@showLinkRequestForm'
        ]);

        Route::post('password/email', [
            'as' => 'access.password.email',
            'uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail'
        ]);

        Route::get('password/reset/{token}', [
            'as' => 'access.password.reset',
            'uses' => 'Auth\ResetPasswordController@showResetForm'
        ]);

        Route::post('password/reset', [
            'as' => 'access.password.reset.post',
            'uses' => 'Auth\ResetPasswordController@reset'
        ]);
    });

    Route::group(['middleware' => ['auth']], function () {
        Route::get('logout', [
            'as'   => 'access.logout',
            'uses' => 'Auth\LoginController@logout',
            'permission' => false,
        ]);
        include('admin_route.php');
    });
});
*/
Route::get('cse', [
    'as'   => 'cse.search',
    'uses' => 'PagesController@getCseSearchGoogle'
]);

Route::post('rating', [
    'as' => 'post.rating',
    'uses' => 'RatingController@postRating'
]);
Route::get('sitemap-master.xml', 'SiteMapController@siteMapMaster')->name('sitemap.master');
Route::get('sitemap.xml', 'SiteMapController@siteMap')->name('sitemap.default');
Route::get('sitemap-mobile.xml', 'SiteMapController@siteMapMobile')->name('sitemap.mobile');
Route::get('sitemap-image.xml', 'SiteMapController@siteMapImage')->name('sitemap.image');
Route::get('tags/{tag_alias}.html', 'TagsController@show')->where('tag_alias', '(.*)?')->name('tags.show');

Route::get('search.htm', [
    'as'   => 'search.index',
    'uses' => 'SearchController@showSearch'
]);

Route::group([
    'prefix' => 'api',
    'as'     => 'api'
],function (){

    Route::post('search',[
        'as'   => 'search',
        'uses' => 'ApiController@getActionChangeType'
    ]);
});

Route::get('tien-ich/{utility}',[
    'as'   => 'estate.utility',
    'uses' => 'EstatesController@getByUtility'
]);

Route::get('ky-gui-nha-dat.html',[
    'as'   => 'page.deposit',
    'uses' => 'PagesController@getDepositEstate'
]);

Route::post('ky-gui-nha-dat.html',[
    'as'   => 'page.postDeposit',
    'uses' => 'PagesController@postDepositEstate'
]);
// Category routes


Route::get('{category}/{district}-{id}', [
    'as'   => 'category.district',
    'uses' => 'DistrictsController@getEstatesByCategoryAndDistrict'
])->where([
    'category' => '(.*)?',
    'district' => '(.*)?',
    'id'       => '[\d]+'
]);

Route::get('{title_alias}_tn{id}', [
    'as'   => 'estate.detail',
    'uses' => 'EstatesController@detail'
])->where([
    'title_alias' => '(.*)?',
    'id'       => '[\d]+'
]);

Route::get('{path_alias}', [
    'as'   => 'categories.show',
    'uses' => 'CategoriesController@show'
])->where('path_alias', '(.*)?');


// Manipulate image routes

Route::get('manipulate/origin/{path}', [
    'as'   => 'origin.manipulate',
    'uses' => 'ImageManipulateController@manipulateOrigin'
])->where([
    'path' => '(.*)?'
]);

Route::post('messages/appointment-schedule.ajax', 'MessageController@postSchedule')->name('messages.schedule');
Route::post('messages/quote.ajax', 'MessageController@postQuote')->name('messages.quote');
Route::post('messages/contact/{id}.ajax', 'MessageController@postSend')->name('messages.post');
// Contact Form
Route::post('contacts/{contact_alias}.html', [
    'as' => 'contacts.send',
    'uses' => 'ContactsController@send'
])->where('contact_alias', '(.*)?');
// Contact Form Project

Route::post('contacts/projects/{project_alias}.html', [
    'as' => 'contacts.project.send',
    'uses' => 'ProjectsController@send'
])->where('project_alias', '(.*)?');

Route::post('tools/district/ward', 'DistrictController@getWard')->name('district.ward');
