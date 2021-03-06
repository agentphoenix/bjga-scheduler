<?php namespace Scheduler\Events;

use App,
	Date,
	File,
	Mail,
	Config,
	Request;
use Sabre\VObject\Component\VCalendar;

class UserEventHandler {

	public function onUserCreated($user, $input)
	{
		// Assign credits to the new account
		$this->assignCredits($user);

		// Set the email data
		$data = array(
			'name'		=> $user->name,
			'site'		=> Request::instance()->root(),
			'email'		=> $user->email,
			'password'	=> $input['password'],
		);

		// Send the email
		Mail::send('emails.userCreated', $data, function($msg) use ($user)
		{
			$msg->to($user->email)
				->subject(Config::get('bjga.email.subject')." Welcome to Brian Jacobs Golf!")
				->replyTo(Config::get('bjga.email.contact'));
		});
	}

	public function onUserDeleted($user)
	{
		// Get right now
		$now = Date::now();

		// Get all the user's future appointments
		$appointments = $user->appointments->filter(function($ua) use ($now)
		{
			return $ua->appointment->start >= $now;
		});

		foreach ($appointments as $ua)
		{
			// Remove the user appointment
			$ua->forceDelete();

			// If it's a lesson, remove the staff appointment too
			if ($ua->appointment->service->isLesson())
			{
				$ua->appointment->forceDelete();
			}
		}
	}

	public function onUserPasswordReminder($data){}

	public function onUserPasswordReset($data){}

	public function onUserRegistered($user, $input)
	{
		if (App::environment() == 'production')
		{
			/*// Subscribe the user to the mailchimp list
			if (isset($input['newsletter_optin']) and $input['newsletter_optin'] == '1')
			{
				// Break the user name out
				$name = explode(' ', $input['name']);
				$firstName = (isset($name[0])) ? $name[0] : false;
				$lastName = (isset($name[1])) ? $name[1] : false;

				// Get the BombBomb instance
				$bombbomb = App::make('scheduler.bombbomb');

				// Subscribe the user
				$result = $bombbomb->addContact(array(
					'eml'		=> $input['email'],
					'firstname'	=> $firstName,
					'lastname'	=> $lastName,
					'listlist'	=> '8334abc4-dd51-dbec-233f-517b664913f3',
				));

				// Get the MailChimp instance
				$mailchimp = App::make('scheduler.mailchimp');

				// Get the list
				$list = $mailchimp->call('lists/list', array('list_name' => 'Members'));

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
			}*/
		}

		// Assign credits to the new account
		$this->assignCredits($user);

		// Set the email data
		$data = array(
			'name'		=> $user->name,
			'site'		=> Request::instance()->root(),
			'email'		=> $user->email,
			'password'	=> $input['password'],
		);

		// Send the email
		Mail::send('emails.userRegistered', $data, function($msg) use ($user)
		{
			$msg->to($user->email)
				->subject(Config::get('bjga.email.subject')." Welcome to Brian Jacobs Golf!")
				->replyTo(Config::get('bjga.email.contact'));
		});
	}

	public function onUserUpdated($user, $input){}

	public function onStaffCreated($staff)
	{
		// Set the calendar we're creating
		$calendarName = str_replace(' ', '', $staff->user->name).'.ics';

		// Get the calendar
		$calendar = new VCalendar;

		// Create the calendar file
		File::put(App::make('path.public')."/calendars/{$calendarName}", $calendar->serialize());

		// Set the email data
		$data = array('name' => $staff->user->name);

		// Send the email
		Mail::send('emails.staffCreated', $data, function($msg) use ($staff)
		{
			$msg->to($staff->user->email)
				->subject(Config::get('bjga.email.subject')." Staff Account Created")
				->replyTo(Config::get('bjga.email.contact'));
		});
	}

	public function onStaffDeleted($staff)
	{
		// Set the calendar we're dealing with
		$calendarName = str_replace(' ', '', $staff->user->name).'.ics';

		// Remove the calendar file
		File::delete(App::make('path.public')."/calendars/{$calendarName}");

		// Set the email data
		$data = array('name' => $staff->user->name);

		// Send the email
		Mail::send('emails.staffDeleted', $data, function($msg) use ($staff)
		{
			$msg->to($staff->user->email)
				->subject(Config::get('bjga.email.subject')." Staff Account Deleted")
				->replyTo(Config::get('bjga.email.contact'));
		});
	}

	public function onStaffUpdated($item){}

	private function assignCredits($user)
	{
		// Get the instance of the credit repo
		$credits = App::make('CreditRepository');

		// Find any credits by email
		$items = $credits->findByEmail($user->email);

		if ($items->count() > 0)
		{
			foreach ($items as $item)
			{
				$updateData = [
					'user_id'	=> $user->id,
					'email'		=> '',
				];

				if ($item->type == 'time')
				{
					$updateData['expires'] = Date::now()->addDay()->addYear()->startOfDay();
				}

				// Update the credit
				$item->update($updateData);
			}
		}
	}

}