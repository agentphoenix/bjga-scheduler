<?php namespace Scheduler\Events;

use Mail,
	Queue,
	Config;

class AppointmentEventHandler {

	public function onCreated($service, $staffAppt, $userAppt, $sendEmail)
	{
		// Update the calendar
		Queue::push('Scheduler\Services\CalendarService', array('model' => $staffAppt));

		if ($sendEmail)
		{
			// Set the data
			$data = array(
				'service'	=> $service->name,
				'date'		=> $staffAppt->start->format(Config::get('bjga.dates.date')),
				'start'		=> $staffAppt->start->format(Config::get('bjga.dates.time')),
				'end'		=> $staffAppt->end->format(Config::get('bjga.dates.time')),
			);

			// Get the user
			$user = $userAppt->user;

			// Email the attendees
			Mail::queue('emails.appointmentCreated', $data, function($message) use ($user)
			{
				$message->to($user->email)
					->subject(Config::get('bjga.email.subject')." Appointment Created");
			});
		}
	}
	
	public function onUpdated($staffAppt, $userAppt)
	{
		// Update the calendar
		Queue::push('Scheduler\Services\CalendarService', array('model' => $staffAppt));

		// Set the data
		$data = array(
			'service'	=> $staffAppt->service->name,
			'date'		=> $staffAppt->start->format(Config::get('bjga.dates.date')),
			'start'		=> $staffAppt->start->format(Config::get('bjga.dates.time')),
			'end'		=> $staffAppt->end->format(Config::get('bjga.dates.time')),
		);

		// Get the user
		$user = $userAppt->user;

		// Email the attendees
		Mail::queue('emails.appointmentUpdated', $data, function($message) use ($user)
		{
			$message->to($user->email)
				->subject(Config::get('bjga.email.subject')." Appointment Updated");
		});
	}

}