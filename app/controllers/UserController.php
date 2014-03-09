<?php

class UserController extends \BaseController {

	/**
	 * Display login form
	 *
	 * @return View
	 */
	public function getLogin()
	{
		return View::make('account.login');
	}


	/**
	 * Process the Login Form
	 *
	 * @return View
	 */
	public function postLogin()
	{
		// Basic Validation
		$required = array(
			'username' => 'required|exists:users,username',
			'password' => 'required'
		);
		$error_message = "Oopsie! Looks like the login you entered are incorrect.";
		
		$validation = Validator::make(Input::all(), $required);
		if ( $validation->fails() ){
			return Redirect::route('login_form')
				->withInput()
				->withMessage($error_message);
		}

		// Authentication
		if ( Auth::attempt(array(
				'username'=>Input::get('username'), 
				'password'=>Input::get('password')
			)) ){
			// temp: change to account view
			return Redirect::route('login_form')
				->withSuccess('You have successfully logged in.');
		} else {
			return Redirect::route('login_form')
				->withMessage($error_message);
		}
	}


	/**
	* Log the user out
	* @return View
	*/
	public function getLogout()
	{
		Auth::logout();
		return Redirect::route('login_form')
			->withSuccess('You have been logged out.');
	}


	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('account.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validation = Validator::make(Input::all(), User::$required, User::$validation_messages);
		if ( $validation->fails() ){
			return Redirect::route('user.create')
				->withInput()
				->withErrors($validation);
		}

		$honeypot = Input::get('hp');
		if ( $honeypot == '' ){

			// Save the new user and log them in
			$user = User::create(array(
				'email' => Input::get('email'),
				'username' => Input::get('username'),
				'password' => Hash::make(Input::get('password'))
			));
			$user = User::where('username', Input::get('username'))->first();
			Auth::login($user);

			// Create their default list
			$list = new Toylist;
			$list->user_id = $user->id;
			$list->save();

			// TODO: redirect to list setup with name and zip fields
			return Redirect::route('user.create')
				->withSuccess('Your account has been setup successfully!');

		} else {
			// it's a bot
			return Redirect::route('user.create')
				->withErrors('It appears you are a bot.');
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


	/**
	* User Signup ajax validation
	*
	* @param string $field
	* @param string $value
	*/
	public function validateSignup()
	{
		if ( Request::ajax() ){
			
			$field = Input::get('field');
			$value = Input::get('value');

			// Set correct rules depending on which field is being validated
			switch ($field){
				case 'email':
					$data = array( 'email' => Input::get('value'));
					$rules = array( 'email' => 'required|email|unique:users,email' );
				break;
				case 'username':
					$data = array( 'username' => Input::get('value'));
					$rules = array( 'username' => 'required|min:3|unique:users,username' );
				break;
				case 'password':
					$data = array( 'password' => Input::get('value'));
					$rules = array( 'password' => 'required|min:6' );
				break;
			}

			$validation = Validator::make($data, $rules);
			if ( $validation->fails() ){
				return Response::json(array('status'=>'error'));
			} else {
				return Response::json(array('status'=>'success'));
			}
		}
	}

}