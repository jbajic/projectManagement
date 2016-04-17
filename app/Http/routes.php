<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    
	Route::get('/', array( 'as' => 'login.get', 'uses' => 'AuthController@getLogin' ));

	Route::post('/login', array( 'as' => 'login.post', 'uses' => 'AuthController@postLogin' ));

	Route::get('/register', array( 'as' => 'register.get', 'uses' => 'AuthController@getRegister' ));

	Route::post('/register', array( 'as' => 'register.post', 'uses' => 'AuthController@postRegister' ));

	Route::get('/resetpassword', array( 'as' => 'reset.password', 'uses' => 'AuthController@resetPassword' ));

	/*
		AUTHENTICATED USERS
	*/

	Route::group(['middleware' => ['auth']], function() {

		Route::get('/logout', array( 'as' => 'logout', 'uses' => 'AuthController@logout' ));

		Route::get('/dashboard', array( 'as' => 'dashboard', 'uses' => 'DashboardController@index' ));

	});

});
