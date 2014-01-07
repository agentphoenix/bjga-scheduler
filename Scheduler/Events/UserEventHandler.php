<?php namespace Scheduler\Events;

use App,
	Mail,
	Request;

class UserEventHandler {

	public function onUserCreated($user, $input)
	{
		// Set the email data
		$data = array(
			'name'		=> $user->name,
			'site'		=> Request::instance()->root(),
			'email'		=> $user->email,
			'password'	=> $input['password'],
		);

		// Send the email
		Mail::queue('emails.users.created', $data, function($msg) use ($user)
		{
			$msg->to($user->email)->subject("Welcome to Brian Jacobs Golf!");
		});
	}

	public function onUserDeleted($user){}

	public function onUserPasswordReminder($data){}

	public function onUserPasswordReset($data){}

	public function onUserRegistered($user, $input)
	{
		if (App::environment() == 'production')
		{
			// MailChimp
		}

		// Set the email data
		$data = array(
			'name'		=> $user->name,
			'site'		=> Request::instance()->root(),
			'email'		=> $user->email,
			'password'	=> $input['password'],
		);

		// Send the email
		Mail::queue('emails.users.registered', $data, function($msg) use ($user)
		{
			$msg->to($user->email)->subject("Welcome to Brian Jacobs Golf!");
		});
	}

	public function onUserUpdated($user, $input){}

}