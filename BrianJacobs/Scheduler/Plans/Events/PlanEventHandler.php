<?php namespace Plans\Events;

use App, Mail, Config;

class PlanEventHandler {

	public function onCreate($plan)
	{
		App::make('NotificationRepository')->create([
			'user_id'	=> $plan->user->id,
			'type'		=> 'plan',
			'category'	=> 'plan',
			'action'	=> 'create',
			'content'	=> "Development plan was created.",
		]);

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
		App::make('NotificationRepository')->create([
			'user_id'	=> $plan->user->id,
			'type'		=> 'plan',
			'category'	=> 'plan',
			'action'	=> 'delete',
			'content'	=> "Development plan was removed.",
		]);
	}

	public function onUpdate($plan, $instructorId)
	{
		App::make('NotificationRepository')->create([
			'user_id'	=> $plan->user->id,
			'type'		=> 'plan',
			'category'	=> 'plan',
			'action'	=> 'update',
			'content'	=> "Development plan was updated.",
		]);

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
