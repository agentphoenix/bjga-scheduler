<?php namespace Plans\Commands;

use Date, Mail, Config;
use Illuminate\Console\Command;

class PlanEmailsCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'scheduler:plan-emails';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Send any emails for development plans.';

	public function __construct()
	{
		parent::__construct();
	}

	public function fire()
	{
		$this->sendNoActivityEmails(30);

		$this->sendNoActivityEmails(60);

		$this->sendNoActivityEmails(90);
	}

	protected function sendNoActivityEmails($days)
	{
		// Get all the plans
		$plans = $this->laravel['PlanRepository']->all();

		// Get today
		$today = Date::now()->startOfDay();

		// Filter by last updated $days days ago
		$plans = $plans->filter(function($p) use ($days, $today)
		{
			return (int) $p->updated_at->startOfDay()->diffInDays($today) === (int) $days;
		});

		if ($plans->count() > 0)
		{
			foreach ($plans as $plan)
			{
				$data = [
					'days'	=> $days,
				];

				Mail::send('emails.plan-inactivity', $data, function($msg) use ($plan)
				{
					$msg->to($plan->user->email)
						->subject(Config::get('bjga.email.subject')." Your Development Plan Activity");
				});
			}
		}
	}

}
