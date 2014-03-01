<?php namespace Scheduler\Events;

use App,
	Mail,
	Config,
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
		Mail::queue('emails.userCreated', $data, function($msg) use ($user)
		{
			$msg->to($user->email)
				->subject(Config::get('bjga.email.subject')." Welcome to Brian Jacobs Golf!");
		});
	}

	public function onUserDeleted($user){}

	public function onUserPasswordReminder($data){}

	public function onUserPasswordReset($data){}

	public function onUserRegistered($user, $input)
	{
		if (App::environment() == 'production' or App::environment() == 'local')
		{
			// Subscribe the user to the mailchimp list
			if (isset($input['mailchimp_optin']) and $input['mailchimp_optin'] == '1')
			{
				// Break the user name out
				$name = explode(' ', $input['name']);
				$firstName = (isset($name[0])) ? $name[0] : false;
				$lastName = (isset($name[1])) ? $name[1] : false;

				// Get the MailChimp instance
				$mailchimp = App::make('scheduler.mailchimp');

				// Get the list
				$list = $mailchimp->call('lists/list', array('list_name' => 'Subscribers'));

				// Subscribe the user
				$result = $mailchimp->call('lists/subscribe', array(
					'id'				=> $list['data'][0]['id'],
					'email'				=> array('email' => $input['email']),
					'merge_vars'		=> array('FNAME' => $firstName, 'LNAME' => $lastName),
					'double_optin'		=> false,
					'update_existing'	=> true,
					'replace_interests'	=> false,
					'send_welcome'		=> false,
				));
			}
		}

		// Set the email data
		$data = array(
			'name'		=> $user->name,
			'site'		=> Request::instance()->root(),
			'email'		=> $user->email,
			'password'	=> $input['password'],
		);

		// Send the email
		Mail::queue('emails.userRegistered', $data, function($msg) use ($user)
		{
			$msg->to($user->email)
				->subject(Config::get('bjga.email.subject')." Welcome to Brian Jacobs Golf!");
		});
	}

	public function onUserUpdated($user, $input){}

	public function onStaffCreated($staff)
	{
		// Set the email data
		$data = array('name' => $staff->user->name);

		// Send the email
		Mail::queue('emails.staffCreated', $data, function($msg) use ($staff)
		{
			$msg->to($staff->user->email)
				->subject(Config::get('bjga.email.subject')." Staff Account Created");
		});
	}

	public function onStaffDeleted($staff)
	{
		// Set the email data
		$data = array('name' => $staff->user->name);

		// Send the email
		Mail::queue('emails.staffDeleted', $data, function($msg) use ($staff)
		{
			$msg->to($staff->user->email)
				->subject(Config::get('bjga.email.subject')." Staff Account Deleted");
		});
	}

	public function onStaffUpdated($item){}

}