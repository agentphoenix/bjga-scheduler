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

	public function studentCancelled($staffAppt, $user, $reason)
	{
		// Get the service
		$service = $staffAppt->service;

		// Set the data
		$data = array(
			'student'	=> $user->name,
			'service'	=> $service->name,
			'date'		=> $staffAppt->start->format(Config::get('bjga.dates.date')),
			'reason'	=> $reason,
		);

		// Email the attendees
		Mail::queue('emails.studentCancelled', $data, function($message) use ($service)
		{
			$message->to($service->staff->user->email)
				->subject(Config::get('bjga.email.subject')." {$service->name} - Student Cancellation");
		});

		// Update the calendar
		Queue::push('Scheduler\Services\CalendarService', array('model' => $staffAppt));
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