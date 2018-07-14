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

Route::get('administrator/login','Admin\AdminController@login');
Route::post('/administrator/login','Admin\AdminController@authenticate');

Route::group(['prefix'=> 'admin','namespace' => 'Admin'], function () {
	Route::get('/dashboard','AdminController@dashboard');
	Route::get('/logout', function () {
        \Auth::logout();
		return redirect('/administrator/login');
	});
});


#This is use for forgot password in WEB API
Route::get('reset_password/{token}', 'Api\AuthController@resetPassword');
Route::post('reset_password/{token}', 'Api\AuthController@updatePassword');