<?php namespace Scheduler\Commands;

use Date,
	Mail,
	Config,
	StaffAppointmentModel;
use Illuminate\Console\Command;

class AppointmentReminderMessageCommand extends Command {

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

		if ($appointments->count() > 0)
		{
			foreach ($appointments as $sa)
			{
				// Get the service
				$service = $sa->service;

				// Make sure it's a lesson or service
				if ($service->isLesson() or $service->isProgram())
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
						'service'	=> $service->name,
						'start'		=> $sa->start->format(Config::get('bjga.dates.time')),
						'end'		=> $sa->end->format(Config::get('bjga.dates.time')),
						'lesson'	=> (bool) $service->isLesson(),
						'due'		=> ($service->isLesson()) ? $sa->userAppointments->first()->present()->due : 0,
					);

					// Set the view
					$view = 'emails.appointmentReminder';

					// Send the email
					Mail::send($view, $data, function($msg) use ($emailsFinal, $service)
					{
						if ($service->isLesson())
						{
							$msg->to($emailsFinal);
						}
						else
						{
							$msg->bcc($emailsFinal);
						}
						
						$msg->subject(Config::get('bjga.email.subject').' Upcoming Appointment Reminder')
							->replyTo($service->staff->user->email);
					});
				}
			}
		}
	}

}