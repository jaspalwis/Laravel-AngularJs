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

Route::get('/', function () {
	return redirect('/home');
	
    //return view('app');
});

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
    Route::auth();
	Route::post('/login', 'CustomAuthController@postLogin');
	
	Route::group(['middleware' => 'auth'], function () {
		Route::get('/home', function () {
			return view('app');
		}); 
	 	
	}); 

	Route::group(['middleware' => 'auth' ], function () {
		// For Logs list 
		Route::group(['prefix' => 'logs', 'middleware'=>'role:superadmin'],function() {
			Route::get('/', 'LogsController@index');
		});
		
		// For Users list 
		Route::group(['prefix' => 'users', 'middleware'=>'role:superadmin'],function() {
			Route::get('/', 'UsersController@index');
			Route::post('/', 'UsersController@store');
			Route::get('/edit/{id}', 'UsersController@edit');
			Route::post('/{id}', 'UsersController@update');
			Route::get('checkusergroup/{id}', 'UsersController@checkusergroup');
			Route::delete('/{id}', 'UsersController@destroy');
		});
		
		// For Service list 
		Route::get('/servicelist/{id?}', 'ServiceLists@index');
		Route::post('/servicelist', 'ServiceLists@store');
		Route::post('/servicelist/{id}', 'ServiceLists@update');
		Route::delete('/servicelist/{id}', 'ServiceLists@destroy');

		// For Jobs 
		Route::get('/jobslists/allservices', 'JobsLists@allservices');
		Route::get('/jobslists/alltechnicinas', 'JobsLists@alltechnicinas');
		Route::get('/jobslists/{id?}', 'JobsLists@index');
		Route::post('/jobslists', 'JobsLists@store');
		Route::post('/jobslists/{id}', 'JobsLists@update');
		Route::delete('/jobslists/{id}', 'JobsLists@destroy');

		Route::get('posts-json', array(  
		  'as' => 'posts-json', 
		  'uses' => 'ServiceLists@ServicePagination' 
		));
		
		
	});
	
	
});




	
// Templates
/* Route::group(array('prefix'=>'/templates/'),function(){
    Route::get('{template}', array( function($template)
    {
        $template = str_replace(".html","",$template);
        View::addExtension('html','php');
        return View::make('templates.'.$template);
    }));
}); */

// Views tpl
Route::group(array('prefix'=>'/views/'),function(){
    Route::get('{tpl}', array( function($tpl)
    {
        $tpl = str_replace(".html","",$tpl);
        View::addExtension('html','php');
        return View::make('views.'.$tpl);
    }));
});

// Views datatables
Route::group(array('prefix'=>'/views/datatables'),function(){
    Route::get('{tpl}', array( function($tpl)
    {
        $tpl = str_replace(".html","",$tpl);
        View::addExtension('html','php');
        return View::make('views.datatables.'.$tpl);
    }));
});

// Views profile
Route::group(array('prefix'=>'/views/profile'),function(){
    Route::get('{tpl}', array( function($tpl)
    {
        $tpl = str_replace(".html","",$tpl);
        View::addExtension('html','php');
        return View::make('views.profile.'.$tpl);
    }));
});


