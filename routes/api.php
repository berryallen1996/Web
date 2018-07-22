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
	Route::post('register', 'Api\AuthController@register');
	Route::post('login', 'Api\AuthController@login');
	Route::post('forgot', 'Api\AuthController@forgot');
	Route::post('verify', 'Api\AuthController@verifyUser');
	Route::post('dropdown', 'Api\AuthController@dropdown');
	Route::post('resend_otp', 'Api\AuthController@resend_otp');
	Route::post('restaurant', 'Api\AuthController@restaurant');
	Route::post('dishes', 'Api\AuthController@dishes');
	Route::post('static', 'Api\AuthController@staticContent');

	Route::post('send', 'Api\AuthController@send');
	Route::group(['middleware' => 'jwt.auth'], function () {
	    Route::get('logout', 'Api\AuthController@logout');
	    Route::post('addAddress', 'Api\AuthController@addAddress');
	    Route::post('addressList', 'Api\AuthController@addressList');
	    Route::post('add_to_cart', 'Api\AuthController@addToCart');
	    Route::post('cart_list', 'Api\AuthController@cartList');
	    Route::post('remove_from_cart', 'Api\AuthController@removeFromCart');
	    Route::post('place_order', 'Api\AuthController@placeOrder');
	});
