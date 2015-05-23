<?php namespace Plans\Events;

use App, Mail, Config;

class StatsEventHandler {

	public function onCreate($stat)
	{
		if ($stat->type == "tournament" and $stat->place <= 3)
		{
			switch ($stat->place)
			{
				case 1:
					$placeNice = "first";
				break;

				case 2:
					$placeNice = "second";
				break;

				case 3:
					$placeNice = "third";
				break;
			}

			sleep(1);

			App::make('StatRepository')->create([
				'type'		=> 'message',
				'goal_id'	=> $stat->goal_id,
				'icon'		=> "place{$stat->place}",
				'notes'		=> "Congratulations on your {$placeNice} place finish at the {$stat->tournament}!",
			]);
		}
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
