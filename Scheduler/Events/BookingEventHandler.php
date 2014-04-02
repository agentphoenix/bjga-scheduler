<?php namespace Scheduler\Events;

use Mail,
	Queue,
	Config;

class BookingEventHandler {

	public function createBlock($user, $appt)
	{
		// Update the calendar
		Queue::push('Scheduler\Services\CalendarService', array('staff' => $user->staff->id));
	}

	public function createLesson($service, $staffAppt, $userAppt)
	{
		// Update the calendar
		Queue::push('Scheduler\Services\CalendarService', array('staff' => $staffAppt->staff->id));

		// Get the user
		$user = $userAppt->user;

		// Set the data
		$data = array(
			'service'		=> $service->name,
			'date'			=> $staffAppt->start->format(Config::get('bjga.dates.date')),
			'start'			=> $staffAppt->start->format(Config::get('bjga.dates.time')),
			'end'			=> $staffAppt->end->format(Config::get('bjga.dates.time')),
			'recurring' 	=> (bool) $service->isRecurring(),
			'additional'	=> $service->occurrences - 1,
			'days'			=> $service->occurrences_schedule,
			'user'			=> $user->name,
		);

		// Email the attendees
		Mail::queue('emails.bookedLesson', $data, function($msg) use ($user, $service)
		{
			$msg->to($user->email)
				->subject(Config::get('bjga.email.subject')." {$service->name} Booked");
		});

		// Email the instructor
		Mail::queue('emails.bookedLessonInstructor', $data, function($msg) use ($service)
		{
			$msg->to($service->staff->user->email)
				->subject(Config::get('bjga.email.subject')." {$service->name} Booking Notification");
		});
	}

	public function createProgram($service, $userAppt)
	{
		// Set the data
		$data = array(
			'service'	=> $service->name,
			'schedule'	=> array(),
		);

		$occurrences = $service->serviceOccurrences->sortBy(function($s){ return $s->start; });

		foreach ($occurrences as $o)
		{
			$data['schedule'][] = array(
				'start'	=> $o->start->format('l F jS, Y, g:ia'),
				'end'	=> $o->end->format('g:ia'),
			);
		}

		// Get the user
		$user = $userAppt->user;

		// Email the attendees
		Mail::queue('emails.bookedProgram', $data, function($msg) use ($user, $service)
		{
			$msg->to($user->email)
				->subject(Config::get('bjga.email.subject')." {$service->name} Enrollment");
		});
	}

	public function studentCancelled($staffAppt, $user, $emails, $reason)
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
		Mail::queue('emails.studentCancelled', $data, function($message) use ($emails, $service)
		{
			$message->to($emails)
				->subject(Config::get('bjga.email.subject')." {$service->name} - Student Cancellation");
		});

		// Update the calendar
		Queue::push('Scheduler\Services\CalendarService', array('staff' => $staffAppt->staff->id));
	}

	public function instructorCancelled($staffAppt, $user, $emails, $reason)
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
		Queue::push('Scheduler\Services\CalendarService', array('staff' => $staffAppt->staff->id));
	}

}