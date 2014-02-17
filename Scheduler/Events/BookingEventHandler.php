<?php namespace Scheduler\Events;

use Mail,
	Queue,
	Config;

class BookingEventHandler {

	public function createBlock($user, $appt)
	{
		Queue::push('Scheduler\Services\CalendarService', array('model' => $user));
	}

	public function createLesson($service, $staffAppt, $userAppt)
	{
		Queue::push('Scheduler\Services\CalendarService', array('model' => $staffAppt));
	}

	public function createProgram($service, $userAppt){}

	public function userCancelledLesson($service, $user)
	{
		// Send an email to the student

		// Send an email to the instructor
	}

	public function userCancelledProgram($service, $user)
	{
		# code...
	}

	public function instructorCancelled($staffAppt, $emails, $reason)
	{
		// Get the service
		$service = $staffAppt->service;

		// Set the data
		$data = array(
			'instructor'	=> $service->staff->user->name,
			'service'		=> $service->name,
			'date'			=> $staffAppt->start->format(Config::get('bjga.dates.date')),
			'reason'		=> $reason,
		);

		// Email the attendees
		Mail::queue('emails.instructorCancelled', $data, function($message) use ($emails, $service)
		{
			$message->to($emails)
				->subject(Config::get('bjga.email.subject')." {$service->name} Schedule Change");
		});

		// Update the calendar
		Queue::push('Scheduler\Services\CalendarService', array('model' => $staffAppt));
	}

}