<?php


/**
* Pages
*/
Route::get('/', array('as'=>'home', 'uses'=>'PageController@getIndex'));
Route::get('search', array('as'=>'find_list', 'uses'=>'SearchController@getIndex'));
Route::post('search', array('as'=>'post_search', 'uses'=>'SearchController@postSearch'));


/**
* Users and Accounts
*/
Route::resource('user', 'UserController');
Route::get('user/create/details', array('as'=>'create_step_two', 'uses'=>'UserController@createTwo'));
Route::post('user/create/details', array('as'=>'create_step_two_post', 'uses'=>'UserController@createTwopost'));
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
Route::resource('loopsy', 'DollController');

