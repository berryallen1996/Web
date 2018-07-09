<?php 
	use Illuminate\Http\Request;

	/*
	|--------------------------------------------------------------------------
	| API Routes
	|--------------------------------------------------------------------------
	|
	| Here is where you can register API routes for your application. These
	| routes are loaded by the RouteServiceProvider within a group which
	| is assigned the "api" middleware group. Enjoy building your API!
	|
	*/
		// Route::post('login','Api\ApiController@login');

	Route::group(['prefix' => 'api', 'namespace' => 'Api'],function($app){
		Route::post('login','ApiController@login');
		Route::post('signup','ApiController@signup');
		Route::post('forgot_password','ApiController@forgot_password');
		Route::post('logout','ApiController@logout');

		Route::group(['middleware' => 'auth:api',],function($app){
			

		});
	});