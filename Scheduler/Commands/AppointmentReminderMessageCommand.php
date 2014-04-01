<?php namespace Scheduler\Commands;

use Date,
	Mail,
	Config,
	StaffAppointmentModel;
use Indatus\Dispatcher\Schedulable,
	Indatus\Dispatcher\ScheduledCommand;

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
		return $scheduler->hourly();
	}

	public function fire()
	{
		// Get right now
		$now = Date::now();

		// Set the target dates
		$target = $now->copy()->minute(0)->second(0)->addDay();
		$targetEnd = $target->copy()->addHour();

		// Get all appointments for the hour
		$appointments = StaffAppointmentModel::where('start', '>=', $target)
			->where('start', '<', $targetEnd)->get();

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

			// Get the service
			$service = $sa->service;

			// Send the email
			Mail::queue('emails.appointmentReminder', $data, function($msg) use ($emailsFinal, $service)
			{
				if ($service->isLesson())
				{
					$msg->to($emailsFinal);
				}
				else
				{
					$msg->bcc($emailsFinal);
				}
				
				$msg->subject(Config::get('bjga.email.subject').' Upcoming Appointment Reminder');
			});
		}
	}

}