<?php

use Loopsy\Forms\Login;

class SessionController extends \BaseController {

	protected $loginForm;

	public function __construct(Login $loginForm)
	{
		$this->loginForm = $loginForm;
	}


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
		try
		{
			$this->loginForm->validate(Input::all());
		}
		catch(Loopsy\Forms\FormValidationException $e)
		{
			$error_message = "Oopsie! Looks like the login you entered is incorrect.";

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
			return Redirect::route('login_form')
				->withSuccess('You have successfully logged in.');
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
		return Redirect::back()
			->with('topmessage','You have been logged out.');
	}

}