<?php

/**
* Pages
*/
Route::get('/', array('as'=>'home', 'uses'=>'PageController@getIndex'));


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