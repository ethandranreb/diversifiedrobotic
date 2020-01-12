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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get ('/', 'LoginController@login_view')->name('login');
Route::get ('/login', 'LoginController@login_view');
Route::post('/login-request', 'LoginController@login_request');
Route::get ('/register', 'RegistrationController@registration_view')->name('register');
Route::post('/register-verify', 'RegistrationController@registration_verify');
Route::post('/register-proceed', 'RegistrationController@registration_proceed');
Route::get ('/newsfeeds', 'DashboardController@newsfeeds_view');
Route::post('/newsfeeds-get', 'DashboardController@newsfeeds_get');
Route::post('/my-newsfeeds-get', 'DashboardController@newsfeeds_get_my');
Route::get ('/profile-edit', 'DashboardController@profile_edit_view');
Route::get ('/profile-status', 'DashboardController@profile_status_view');
Route::post('/profile-status-update', 'DashboardController@profile_status_update');
Route::post('/profile-save', 'DashboardController@profile_save');
Route::get ('/profile-my-newsfeeds', 'DashboardController@profile_my_newsfeeds');
Route::post('/logout-request', 'DashboardController@logout_request');
Route::post('/user-info', 'DashboardController@user_info');
Route::post('/post-new-save', 'DashboardController@post_new_save');
Route::post('/post-share', 'DashboardController@post_share');
Route::post('/post-like', 'DashboardController@post_like');
Route::post('/post-save-comment', 'DashboardController@post_save_comment');
// Route::post('/post-edit', 'DashboardController@post_edit');
// Route::post('/post-delete', 'DashboardController@post_delete');