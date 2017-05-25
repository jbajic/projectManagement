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

	// test route REMOVE IN PRODUCTION
	Route::get('/test', array( 'uses' => 'DashboardController@test' ));
    
	Route::get('/', array( 'as' => 'login.get', 'uses' => 'AuthController@getLogin' ));

	Route::post('/login', array( 'as' => 'login.post', 'uses' => 'AuthController@postLogin', 'middleware' => ['api'] ));

	Route::get('/register', array( 'as' => 'register.get', 'uses' => 'AuthController@getRegister' ));

	Route::post('/register', array( 'as' => 'register.post', 'uses' => 'AuthController@postRegister' ));

	Route::get('/resetpassword', array( 'as' => 'reset.password', 'uses' => 'AuthController@resetPassword' ));


	/**

	*	**	AUTHENTICATED USERS
	
	*/

	Route::group(['middleware' => ['auth']], function() {

		Route::get('/logout', array( 'as' => 'logout.get', 'uses' => 'AuthController@logout' ));

		Route::get('/dashboard', array( 'as' => 'dashboard.index', 'uses' => 'DashboardController@index' ));

		/**
	
		*	**	USERS

		*/

		Route::post('/profile/search', array( 'uses' => 'ProfileController@search' ));

		Route::get('profile/search', array( 'uses' => 'ProfileController@displaySearchResults', 'as' => 'profile.displaySearchResults' ));

		Route::post('/profile', array( 'uses' => 'ProfileController@changeAvatar', 'as' => 'profile.changeAvatar' ));

		Route::post('/profile/addFriend', array( 'uses' => 'ProfileController@addFriend' ));

		Route::post('/profile/rescindInvitation', array( 'uses' => 'ProfileController@rescindInvitation' ));

		Route::post('/profile/removeFriend', array( 'uses' => 'ProfileController@removeFriend' ));

		Route::post('/profile/acceptFriend', array( 'uses' => 'ProfileController@acceptFriend' ));

		Route::post('/profile/refuseFriend', array( 'uses' => 'ProfileController@refuseFriend' ));

		Route::resource('profile', 'ProfileController',
				['except' => ['create', 'store', 'delete']]);

		/**
		
		*	**	PROJECTS

		*/

		Route::put('/project/changeStatus/{id}', array( 'uses' => 'ProjectController@changeStatus', 'as' => 'project.changeStatus' ));

		Route::resource('project', 'ProjectController',
			['except' => ['index']]);

		Route::post('/project/{project_id}/task/checkTask', array( 'uses' => 'TaskController@checkTask' ));

		Route::post('/project/{project_id}/task/deleteCategory', array( 'uses' => 'TaskController@deleteCategory' ));

		Route::post('/project/{project_id}/task/addCategory', array( 'uses' => 'TaskController@addCategory' ));

		Route::post('/project/{project_id}/task/getCategory', array( 'uses' => 'TaskController@getCategory' ));

		Route::post('/project/{project_id}/task/updateCategory', array( 'uses' => 'TaskController@updateCategory' ));

		Route::resource('project.task', 'TaskController',
			['except' => ['edit', 'index']]);
	
	});

});

Route::group(['middleware' => ['api'],'prefix' => 'api'], function () {

    Route::post('register', 'APIController@register');

    Route::post('login', 'APIController@login');

    Route::group(['middleware' => 'jwt-auth'], function () {

        Route::get('synchronize-users', 'APIController@synchronizeUsers');

        Route::get('synchronize-projects', 'APIController@synchronizeProjects');

        Route::post('create-project', 'APIController@createProject');

        Route::post('create-task', 'APIController@createTask');
    });
});
