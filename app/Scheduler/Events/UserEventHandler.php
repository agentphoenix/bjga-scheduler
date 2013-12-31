<?php namespace Scheduler\Events;

class UserEventHandler {

	public function onUserCreated($data)
	{
		// Email
	}

	public function onUserDeleted($data)
	{
		# code...
	}

	public function onUserPasswordReminder($data)
	{
		# code...
	}

	public function onUserPasswordReset($data)
	{
		# code...
	}

	public function onUserRegistered($data)
	{
		// MailChimp

		// Send an email to the user welcoming them to BJG
		Mail::queue();
	}

	public function onUserUpdated($data)
	{
		# code...
	}

}