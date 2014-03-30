<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');

	protected $fillable = array(
		'email', 'password', 'username', 'name', 'zip_code', 'bio', 'group_id', 'latitude', 'longitude', 'slug'
	);

	// Validation rules
	public static $required = array(
		'email' => 'required|email|unique:users,email',
		'username' => 'required|min:3|unique:users,username',
		'password' => 'required|min:6',
		'age' => 'accepted'
	);
	// Validation messages
	public static $validation_messages = array(
		'email.required' => 'Please provide your email',
		'email.email' => 'Please provide a valid email',
		'email.unique' => 'Looks like your email is already taken',
		'username.required'=> 'Please provide a user name',
		'username.unique' => 'Sorry, that user name has been taken. Try another one.',
		'password.required' => 'Please provide a password',
		'password.min' => 'Your password needs to be at least 6 characters',
		'age.accepted' => 'Please acknowledge that you are over 16 years of age.'
	);

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}

	/**
	 * Get the users group
	 *
	 * @return string
	 */
	public function group()
	{
		return $this->belongsTo('group');
	}
	
	/**
	 * Get the users list
	 *
	 * @return string
	 */
	public function toylist()
	{
		return $this->hasOne('ToyList');
	}

}