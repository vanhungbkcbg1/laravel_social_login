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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/{provider}/callback', 'SocialController@callBack')->name('login_callback');
Route::get('/redirect/{provider}', 'SocialController@redirect')->name('login_provider');

Route::get('demo-request','DemoRequestController@index')->name("demo_request_index");
Route::post('demo-request','DemoRequestController@store')->name("demo_request_store");
Route::get('decorator','DecoratorController@index')->name("decorator_index");
Route::get('oauth','OAuthController@index')->name("oauth_index");
Route::get('oauth/my_callback','OAuthController@callBack')->name("oauth_callback");

Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles','RoleController');
    Route::resource('users','UserController');
    Route::resource('products','ProductController');
    Route::resource('posts','PostController');
    Route::post('/posts/{post}/comments','PostController@addComment')->name("posts.add_comment");
});
