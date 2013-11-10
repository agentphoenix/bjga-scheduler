<?php namespace Scheduler\Validators;

use BaseValidator;

class Staff extends BaseValidator {

	public static $rules = array(
		'user_id'	=> 'required',
		'access'	=> 'required',
	);

	public static $messages = array(
		'user_id.required'	=> "Select a user",
		'access.required'	=> "Enter an access level",
	);

}