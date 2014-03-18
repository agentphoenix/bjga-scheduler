<?php namespace Scheduler\Commands;

use Date,
	Mail,
	Config,
	StaffAppointmentModel;
use Indatus\Dispatcher\Schedulable,
	Indatus\Dispatcher\ScheduledCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ThankYouMessageCommand extends ScheduledCommand {

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

	public function schedule(Schedulable $scheduler)
	{
		return $scheduler->hourly();
	}

	public function fire()
	{
		// Get now
		$now = Date::now();

		// Set the target times
		$target = $now->copy()->minute(0)->second(0)->subHours(12);
		$targetEnd = $target->copy()->addHour();

		// Get all appointments for today
		$appointments = StaffAppointmentModel::where('end', '>=', $target)
			->where('end', '<', $targetEnd)->get();

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
			);

			// Send the email
			Mail::queue('emails.appointmentReminder', $data, function($msg) use ($emailsFinal)
			{
				$msg->to($emailsFinal)
					->subject(Config::get('bjga.email.subject').' Thank You for Choosing Brian Jacobs Golf');
			});
		}
	}

}