<?php
namespace Loopsy\Forms;

class Login extends FormValidator {

	protected $rules = array(
		'username' => 'required|exists:users,username',
		'password' => 'required'
	);

}