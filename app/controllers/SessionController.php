<?php

use Loopsy\Entities\User\User;

class SessionController extends \BaseController {

	/**
	 * Show the Login Form
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('account.login');
	}


	/**
	 * Process the Login Form
	 *
	 * @return Response
	 */
	public function store()
	{
		$rules = array(
			'username' => 'required|exists:users,username',
			'password' => 'required'
		);
		$validation = Validator::make(Input::all(), $rules);
		$error_message = "Oopsie! Looks like the login you entered is incorrect.";

		if ( $validation->fails() ){
			if ( Request::ajax() ){
				return Response::json(array('status'=>'error', 'message'=>$error_message));
			} else {
				return Redirect::route('login_form')
					->withInput()
					->withMessage($error_message);
			}
		}

		// Authentication
		if ( Auth::attempt(array(
				'username'=>Input::get('username'), 
				'password'=>Input::get('password')
			)) ){
			return Redirect::route('user.edit', array('user'=>Auth::user()->slug));
		} else {
			return Redirect::route('login_form')
				->withMessage($error_message);
		}
	}


	/**
	 * Logout
	 *
	 * @return Response
	 */
	public function destroy()
	{
		Auth::logout();
		return Redirect::route('login_form')
			->with('topmessage','You have been logged out.');
	}

}