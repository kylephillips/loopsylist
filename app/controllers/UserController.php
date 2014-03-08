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

			// User is logged in, update last login
			$user = Auth::user();
			$user->last_login = date('Y-m-d G:i:s');
			$user->save();

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
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
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

}