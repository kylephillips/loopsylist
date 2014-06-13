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
Route::get('validate-signup', array('as'=>'validate_signup', 'uses'=>'UserController@validateSignup'));


/**
* Password Resets
*/
Route::get('forgot-password', array('as'=>'get_remind', 'uses'=>'RemindersController@getRemind'));
Route::post('forgot-password', array('as'=>'post_remind', 'uses'=>'RemindersController@postRemind'));
Route::get('reset-password/{token}', array('as'=>'get_reset', 'uses'=>'RemindersController@getReset'));
Route::post('reset-password/{token}', array('as'=>'post_reset', 'uses'=>'RemindersController@postReset'));


/**
* Sessions
*/
Route::get('login', array('as'=>'login_form', 'uses'=>'SessionController@create'));
Route::post('login', array('as'=>'login', 'uses'=>'SessionController@store'));
Route::get('logout', array('as'=>'logout', 'uses'=>'SessionController@destroy'));


/**
* Password Resets
*/
Route::get('forgot-password', array('as'=>'get_remind', 'uses'=>'RemindersController@getRemind'));
Route::post('forgot-password', array('as'=>'post_remind', 'uses'=>'RemindersController@postRemind'));
Route::get('reset-password/{token}', array('as'=>'get_reset', 'uses'=>'RemindersController@getReset'));
Route::post('reset-password/{token}', array('as'=>'post_reset', 'uses'=>'RemindersController@postReset'));


/**
* Lists
*/
Route::resource('list', 'ListController');
Route::get('save-switch', array('as'=>'save_switch', 'uses'=>'ListController@update'));
Route::get('user-dolls', array('as'=>'user_dolls', 'uses'=>'ListController@getUserDolls'));
Route::get('list-order', array('as'=>'list_order', 'uses'=>'ListController@reorder'));


/**
* Wishlist
*/
Route::resource('wishlist', 'WishlistController');


/**
* Toys (loopsies)
*/ 
Route::resource('loopsy', 'DollController');
Route::get('loopsy-image', array('as'=>'loopsy_image', 'uses'=>'DollController@getImage'));





