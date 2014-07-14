<?php namespace Scheduler\Events;

use Mail,
	Config;

class CreditEventHandler {

	public function onCreate($credit, $input)
	{
		if ( ! empty($credit->user_id) or ! empty($credit->email))
		{
			// Compile the basic data
			$data = [
				'type'	=> $credit->type,
				'value'	=> $credit->present()->value,
			];

			// Compile the user account info
			if ( ! empty($credit->user_id))
			{
				$email = $credit->user->email;
				$data['subject'] = $subject = "Credit Has Been Added to Your Account";
				$data['email'] = "user";
			}

			// Compile the email address info
			if ( ! empty($credit->email))
			{
				$email = $credit->email;
				$data['subject'] = $subject = "You've Been Given Credit with Brian Jacobs Golf";
				$data['email'] = "email";
			}

			// Send the email
			Mail::queue('emails.creditAdded', $data, function($msg) use ($credit, $email, $subject)
			{
				$msg->to($email)
					->subject(Config::get('bjga.email.subject')." {$subject}")
					->replyTo(Config::get('bjga.email.contact'));
			});
		}
	}

	public function onDelete($credit)
	{
		// Email the user

		// Email the email address
	}

	public function onUpdate($credit, $input)
	{
		// Email the user

		// Email the email address
	}

}