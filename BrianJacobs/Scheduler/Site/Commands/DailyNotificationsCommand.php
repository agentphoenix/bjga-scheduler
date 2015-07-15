<?php namespace Scheduler\Commands;

use Str,
	Date,
	Mail,
	Config,
	UserModel as User;
use Illuminate\Console\Command;

class DailyNotificationsCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'scheduler:notifications';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Send the daily notifications emails.';

	public function __construct()
	{
		parent::__construct();
	}

	public function fire()
	{
		$this->sendStudentNotifications();

		$this->sendInstructorNotifications();
	}

	public function sendStudentNotifications()
	{
		// Get the user repo
		$usersRepo = app('UserRepository');

		// Get yesterday's date
		$date = Date::now()->subDay();

		foreach ($usersRepo->getStudentsWithDevelopmentPlans() as $user)
		{
			if ($user->countNotificationsByDate($date) > 0)
			{
				//$output = $user->name." has ".$user->countNotificationsByDate($date)." ".Str::plural('notification', $user->countNotificationsByDate($date)).".";
				
				//$this->info($output);

				$hasNotifications = false;

				$data = [
					'name' => $user->present()->firstName,
					'userId' => $user->id,
					'date' => $date->format('l F jS, Y'),
					'notifications' => [
						'planCreate' => 0,
						'planUpdate' => 0,
						'goalCreate' => 0,
						'goalUpdate' => 0,
						'statCreate' => 0,
						'statUpdate' => 0,
						'commentCreate' => 0,
						'commentUpdate' => 0,
					]
				];

				foreach ($user->getNotificationsByDate($date) as $n)
				{
					if ($n->type == 'plan')
					{
						switch ($n->category)
						{
							case 'plan':
								if ($n->action == 'create')
								{
									$data['notifications']['planCreate']++;
								}

								if ($n->action == 'update')
								{
									$data['notifications']['planUpdate']++;
								}
							break;

							case 'goal':
								if ($n->action == 'create')
								{
									$data['notifications']['goalCreate']++;
									$hasNotifications = true;
								}

								if ($n->action == 'update')
								{
									$data['notifications']['goalUpdate']++;
									$hasNotifications = true;
								}
							break;

							case 'stats':
								if ($n->action == 'create')
								{
									$data['notifications']['statCreate']++;
									$hasNotifications = true;
								}

								if ($n->action == 'update')
								{
									$data['notifications']['statUpdate']++;
									$hasNotifications = true;
								}
							break;

							case 'comment':
								if ($n->action == 'create')
								{
									$data['notifications']['commentCreate']++;
									$hasNotifications = true;
								}

								if ($n->action == 'update')
								{
									$data['notifications']['commentUpdate']++;
									$hasNotifications = true;
								}
							break;
						}
					}
				}

				if ($hasNotifications)
				{
					Mail::send('emails.notifications-student', $data, function($message) use ($user)
					{
						$message->to($user->email)
							->subject(Config::get('bjga.email.subject')." Daily Notifications Digest");
					});
				}
			}
		}
	}

	public function sendInstructorNotifications()
	{
		# code...
	}

}
