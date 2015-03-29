<?php namespace Scheduler\Validators;

class LocationValidator extends FormBaseValidator {

	protected $rules = [
		'name'		=> 'required',
		'address'	=> 'required',
		'phone'		=> 'required',
		'url'		=> 'required',
	];

	protected $messages = [
		'name.required' => "Please enter a location's name",
		'address.required' => "Please enter the location's address",
		'phone.required' => "Please enter the location's phone number",
		'url.required' => "Please enter the location's website",
	];

}