<?php namespace Plans\Events;

use App, Mail, Config;

class PlanEventHandler {

	public function onCreate($plan)
	{
		$data = [
			'name'	=> explode(' ', $plan->user->name)[0],
		];

		// Send the email
		Mail::send('emails.plan-created', $data, function($msg) use ($plan)
		{
			$msg->to($plan->user->email)
				->subject(Config::get('bjga.email.subject')." Your Personal Development Plan")
				->replyTo(Config::get('bjga.email.contact'));
		});
	}

	public function onDelete($plan)
	{
		//
	}

	public function onUpdate($plan, $instructorId)
	{
		// Get the instructor
		$instructor = App::make('StaffRepository')->find($instructorId);

		$data = [
			'name'	=> $plan->user->name,
			'staff' => explode(' ', $instructor->user->name)[0],
		];

		// Send the email
		Mail::send('emails.plan-instructor-added', $data, function($msg) use ($instructor)
		{
			$msg->to($instructor->user->email)
				->subject(Config::get('bjga.email.subject')." You Have Been Added to a Development Plan")
				->replyTo(Config::get('bjga.email.contact'));
		});
	}

}
