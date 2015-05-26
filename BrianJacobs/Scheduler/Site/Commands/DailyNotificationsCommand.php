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
		// Get yesterday's date
		$date = Date::now()->subDay();

		foreach (User::all() as $user)
		{
			if ($user->countNotificationsByDate($date) > 0)
			{
				$output = $user->name." has ".$user->countNotificationsByDate($date)." ".Str::plural('notification', $user->countNotificationsByDate($date)).".";
				
				$this->info($output);

				$data = [];

				Mail::send('emails.notifications', $data, function($message) use ($user)
				{
					$message->to($user->email)
						->subject(Config::get('bjga.email.subject')." Daily Notifications Digest");
				});
			}
		}
	}

}
