<?php namespace Scheduler\Validators;

use BaseValidator;

class Service extends BaseValidator {

	public static $rules = array(
		'category_id'	=> 'required',
		'staff_id'		=> 'required',
		'name'			=> 'required',
		'user_limit'	=> 'required|integer',
		'occurrences'	=> 'required|integer',
	);

	public static $messages = array(
		'category_id.required'	=> "Select a category",
		'staff_id.required'		=> "Select a staff member",
		'name.required'			=> "Enter a name for the service",
		'user_limit.required'	=> "Enter the number of users allowed for this service",
		'user_limit.integer'	=> "User limit must be a number",
		'occurrences.required'	=> "Enter the number of occurrences for this service",
		'occurrences.integer'	=> "Occurrences must be a number",
	);

}