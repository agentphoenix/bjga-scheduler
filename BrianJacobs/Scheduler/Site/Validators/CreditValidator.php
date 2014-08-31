<?php namespace Scheduler\Validators;

class CreditValidator extends FormBaseValidator {

	protected $rules = [
		'type'	=> 'required|in:time,money',
		'value'	=> 'required|numeric',
	];

	protected $messages = [
		'type.required' => "Please select a type",
		'type.in' => "Please select a valid credit type",
		'value.required' => "Please enter a value",
		'value.numeric' => "Please enter a valid numerical value",
	];

}