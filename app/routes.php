<?php

Route::get('/', function()
{
	return View::make('hello');
});


/**
* Users and Accounts
*/
Route::resource('user', 'UserController');
Route::get('login', array('as'=>'login_form', 'uses'=>'UserController@getLogin'));
Route::post('login', array('as'=>'login', 'uses'=>'UserController@postLogin'));
Route::get('logout', array('as'=>'logout', 'uses'=>'UserController@getLogout'));
Route::get('validate-signup', array('as'=>'validate_signup', 'uses'=>'UserController@validateSignup'));

/**
* Lists
*/



/**
* Toys (loopsies)
*/ 
Route::resource('loopsie', 'DollController');