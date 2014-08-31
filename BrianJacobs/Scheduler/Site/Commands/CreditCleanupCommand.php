<?php namespace Scheduler\Commands;

use App,
	Date,
	Mail,
	Config;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption,
	Symfony\Component\Console\Input\InputArgument;

class CreditCleanupCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'scheduler:credits';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Do a cleanup of credits and send reminders';

	protected $credits;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		$this->credits = App::make('CreditRepository');
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		// Remove any credits that have expired
		$this->credits->removeExpired(Date::now()->startOfDay());

		// Remove any credits that have been used but not cleaned up
		$this->credits->removeClaimed();

		// Send the first reminder about credits to be used (90 days)
		$this->creditReminderFirst();

		// Send the final reminder about credits to be used (30 days)
		$this->creditReminderFinal();
	}

	protected function creditReminderFirst()
	{
		// Get everything that expires 90 days from today
		$items = $this->credits->findByDate('expires', Date::now()->addDays(90)->startOfDay());

		if ($items->count() > 0)
		{
			foreach ($items as $item)
			{
				// Compile the data for the reminder email
				$data = [
					'value'	=> $item->present()->remainingLong,
					'date'	=> $item->present()->expires,
				];

				// Send the email
				Mail::queue('emails.creditReminderFirst', $data, function($msg) use ($item)
				{
					$msg->to($item->user->email)
						->subject(Config::get('bjga.email.subject')." Credit Expiration Notice (90 Days)")
						->replyTo(Config::get('bjga.email.contact'));
				});
			}
		}
	}

	protected function creditReminderFinal()
	{
		// Get everything that expires 30 days from today
		$items = $this->credits->findByDate('expires', Date::now()->addDays(30)->startOfDay());

		if ($items->count() > 0)
		{
			foreach ($items as $item)
			{
				// Compile the data for the reminder email
				$data = [
					'value'	=> $item->present()->remainingLong,
					'date'	=> $item->present()->expires,
				];

				// Send the email
				Mail::queue('emails.creditReminderFinal', $data, function($msg) use ($item)
				{
					$msg->to($item->user->email)
						->subject(Config::get('bjga.email.subject')." Credit Expiration Final Notice (30 Days)")
						->replyTo(Config::get('bjga.email.contact'));
				});
			}
		}
	}

}