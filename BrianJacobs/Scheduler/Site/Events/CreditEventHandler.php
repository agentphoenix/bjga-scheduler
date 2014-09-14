<?php namespace Scheduler\Events;

use Mail,
	Config;

class CreditEventHandler {

	public function onCreate($credit, $input)
	{
		if ((int) $credit->user_id !== 0 or ! empty($credit->email))
		{
			// Compile the basic data
			$data = [
				'type'	=> $credit->type,
				'value'	=> $credit->present()->valueLong,
			];

			// Compile the user account info
			if ((int) $credit->user_id !== 0)
			{
				// Set the email info
				$email = $credit->user->email;
				$data['subject'] = $subject = "Credit Has Been Added to Your Account";
				$data['email'] = "user";

				// Start the expiration timer
				if ($credit->type == 'time')
				{
					$credit->update(['expires' => \Date::now()->addDay()->addYear()->startOfDay()]);
				}
			}

			// Compile the email address info
			if ( ! empty($credit->email))
			{
				$email = $credit->email;
				$data['subject'] = $subject = "You've Been Given Credit with Brian Jacobs Golf";
				$data['email'] = "email";
			}

			// Send the email
			Mail::send('emails.creditAdded', $data, function($msg) use ($email, $subject)
			{
				$msg->to($email)
					->subject(Config::get('bjga.email.subject')." {$subject}")
					->replyTo(Config::get('bjga.email.contact'));
			});
		}
	}

	public function onDelete($credit)
	{
		if ((int) $credit->user_id !== 0 or ! empty($credit->email))
		{
			// Compile the basic data
			$data = [
				'type'	=> $credit->type,
				'value'	=> $credit->present()->valueLong,
			];

			// Compile the user account info
			if ((int) $credit->user_id !== 0)
			{
				// Set the email info
				$email = $credit->user->email;
				$data['subject'] = $subject = "Credit Has Been Removed from Your Account";
				$data['email'] = "user";
			}

			// Compile the email address info
			if ( ! empty($credit->email))
			{
				$email = $credit->email;
				$data['subject'] = $subject = "Your Credit with Brian Jacobs Golf Has Been Removed";
				$data['email'] = "email";
			}

			// Send the email
			Mail::send('emails.creditRemoved', $data, function($msg) use ($email, $subject)
			{
				$msg->to($email)
					->subject(Config::get('bjga.email.subject')." {$subject}")
					->replyTo(Config::get('bjga.email.contact'));
			});
		}
	}

	public function onUpdate($credit, $input){}

}