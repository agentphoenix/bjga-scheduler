<?php namespace Scheduler\Commands;

use Date,
	Mail,
	Config,
	StaffAppointmentModel;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ThankYouMessageCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'scheduler:thankyou';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Send thank you messages to any students who had a lesson today';

	public function __construct()
	{
		parent::__construct();
	}

	public function fire()
	{
		\Log::info("Appointment thank you command");

		// Get now
		$now = Date::now();

		// Set the target times
		$target = $now->copy()->minute(0)->second(0)->subHours(12);
		$targetEnd = $target->copy()->addHour();

		// Get all appointments for today
		$appointments = StaffAppointmentModel::where('end', '>=', $target)
			->where('end', '<', $targetEnd)->get();

		if ($appointments->count() > 0)
		{
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
					'service' => $sa->service->name,
					'type' => $sa->service->category,
				);

				// Get the service
				$service = $sa->service;

				// Get a random number
				$number = mt_rand(1, 5);

				// Set the view
				$view = "emails.appointmentThankYou{$number}";

				// Send the email
				Mail::send($view, $data, function($message) use ($emailsFinal, $service)
				{
					if ($service->isLesson())
					{
						$message->to($emailsFinal);
					}
					else
					{
						$message->bcc($emailsFinal);
					}

					// Set the subject
					$subject = Config::get('bjga.email.subject');
					$subject.= ' Thank You for Choosing Brian Jacobs Golf';

					$message->subject($subject)
						->replyTo($service->staff->user->email);
				});
			}
		}
	}

}