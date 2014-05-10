<?php

class UserController extends \BaseController {

	public function __construct()
	{
		$this->beforeFilter('auth', array('only' => array('edit','update','destroy')) );
	}


	/**
	 * Show the Signup Form
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('account.create');
	}


	/**
	 * Store the new User
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
				'password' => Hash::make(Input::get('password')),
				'slug' => Str::slug(Input::get('username'))
			));
			$user = User::where('username', Input::get('username'))->first();
			Auth::login($user);

			// Create their default list
			$list = new Toylist;
			$list->user_id = $user->id;
			$list->save();


			return Redirect::route('create_step_two_post');

		} else {
			// it's a bot
			return Redirect::route('user.create')
				->withErrors('It appears you are a bot.');
		}
	}


	/** 
	* Step Two of the signup process (other details)
	*
	* @return View
	*/
	public function createTwo()
	{
		if ( Auth::check() ) {
			return View::make('account.create-two');
		} else {
			return Redirect::route('user.create');
		}
	}


	/** 
	* Store user details (signup step 2)
	*
	* @return View
	*/
	public function createTwopost()
	{
		if ( Auth::check() ){

			// Validation requirements
			$required = array(
				'name' => 'required|min:3',
				'zip' => 'required|min:5|numeric'
			);
			$message = array(
				'zip.min' => 'Your zip code should be a 5-digit number'
			);
			$validation = Validator::make(Input::all(), $required, $message);
			if ( $validation->fails() ){
				return Redirect::route('create_step_two_post')
					->withInput()
					->withErrors($validation);
			}

			// Save User Details
			$user = Auth::user();
			if ( Input::get('name') ) $user->name = Input::get('name');
			if ( Input::get('zip') ) $user->zip_code = Input::get('zip');
			if ( Input::get('latitude') ) $user->latitude = Input::get('latitude');
			if ( Input::get('longitude') ) $user->longitude = Input::get('longitude');
			if ( Input::get('city') ) $user->city = Input::get('city');
			if ( Input::get('state') ) $user->state = Input::get('state');
			if ( Input::get('bio') ) $user->bio = Input::get('bio');
			$user->save();

			// Save List Visibility Preference
			$list = Toylist::where('user_id', $user->id)->firstOrFail();
			$list->visibility = ( Input::get('visibility') ) ? 'public' : 'private';
			$list->save();

			return Redirect::route('list.edit', array('id'=>$user->slug));
		} else {
			return Redirect::route('user.create');
		}
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		// Make sure this is this user's edit URL (auth filter applied in construct)
		$user = Auth::user();
		$visibility = $user->toylist->visibility;
		$public = ( $visibility == 'public' ? true : false );

		if ( $user->slug != $id ){
			return Redirect::route('user.edit', array($id=>$user->slug));
		}
		return View::make('account.edit')
			->with('user', $user)
			->with('public', $public);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$user = User::findorfail($id);
		
		if ( Input::get('email') ) {
			try
			{
				$user->email = Input::get('email');
				$user->save();
			}
			catch (\Illuminate\Database\QueryException $e)
			{
				return Redirect::route('user.edit', array('user'=> $user->slug))
					->withInput()
					->withErrors('The email you have entered is already in use.');
			}
		}

		if ( Input::get('password') ){
			$rules = array(
				'password' => 'required|min:6|confirmed',
				'password_confirmation' => 'required'
			);
			$validation = Validator::make(Input::all(), $rules);
			if ( $validation->fails() ){
				return Redirect::route('user.edit', array('user'=> $user->slug))
					->withInput()
					->withErrors($validation);
			} else {
				$user->password = Hash::make(Input::get('password'));
			}
		}

		$list = $user->toylist;
		if ( Input::get('visibility') ){
			$list->visibility = 'public';
		} else {
			$list->visibility = 'private';
		}
		$list->save();

		if ( Input::get('name') ) $user->name = Input::get('name');
		if ( Input::get('zip') ) $user->zip_code = Input::get('zip');
		if ( Input::get('latitude') ) $user->latitude = Input::get('latitude');
		if ( Input::get('longitude') ) $user->longitude = Input::get('longitude');
		if ( Input::get('city') ) $user->city = Input::get('city');
		if ( Input::get('state') ) $user->state = Input::get('state');
		if ( Input::get('bio') ) $user->bio = Input::get('bio');
		
		$user->save();

		return Redirect::route('user.edit', array('user'=> $user->slug))
			->with('success','success');
	}


	/**
	 * Delete the User
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$user = User::findorfail($id);
		$user->delete();
		return Response::json(array('status'=>'success'));
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