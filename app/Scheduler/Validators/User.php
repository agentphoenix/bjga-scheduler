<?php namespace Scheduler\Validators;

use BaseValidator;

class User extends BaseValidator {

	public static $rules = array(
		'name'				=> 'required',
		'email'				=> 'required|email',
		'password'			=> 'required',
		'password_confirm'	=> 'required|same:password',
		'phone'				=> 'required',
	);

	public static $messages = array(
		'name.required'				=> "Enter a name",
		'email.required'			=> "Enter an email address",
		'email.email'				=> "Email addresses must be in the proper format (address@domain)",
		'password.required'			=> "Enter a password",
		'password_confirm.required'	=> "Enter the password again",
		'password_confirm.same'		=> "The passwords don't match",
		'phone.required'			=> "Enter a phone number",
	);

}