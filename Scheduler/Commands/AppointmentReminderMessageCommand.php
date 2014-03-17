<?php namespace Scheduler\Commands;

use Date,
	Mail,
	Config,
	StaffAppointmentModel;
use Indatus\Dispatcher\Schedulable,
	Indatus\Dispatcher\ScheduledCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class AppointmentReminderMessageCommand extends ScheduledCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'scheduler:reminders';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Send reminder emails to any students who have appointments today';

	public function __construct()
	{
		parent::__construct();
	}

	public function schedule(Schedulable $scheduler)
	{
		return $scheduler->daily()->hours(3)->minutes(15);
	}

	public function fire()
	{
		// Get today
		$today = Date::now();

		// Get all appointments for today
		$appointments = StaffAppointmentModel::where('start', '>=', $today->startOfDay())
			->where('end', '<=', $today->endOfDay())->get();

		foreach ($appointments as $sa)
		{
			// Start an array for holding email address
			$emails = array();

			foreach ($sa->userAppointments as $ua)
			{
				// Get the email address
				$emails[] = $ua->user->email;
			}

			// Make sure we have a unique list of addresses
			$emailsFinal = array_unique($emails);

			// Build the data to be used in the email
			$data = array(
				'service'	=> $sa->service->name,
				'start'		=> $sa->start->format(Config::get('bjga.dates.time')),
				'end'		=> $sa->end->format(Config::get('bjga.dates.time')),
			);

			// Send the email
			Mail::queue('emails.appointmentReminder', $data, function($msg) use ($emailsFinal)
			{
				$msg->to($emailsFinal)
					->subject(Config::get('bjga.email.subject').' Upcoming Appointment Reminder');
			});
		}

		$this->info("\nReminder emails have been sent!");
	}

}