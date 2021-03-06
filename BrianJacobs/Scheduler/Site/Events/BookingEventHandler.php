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

		// Start to build some appointment information
		$appointments = [];

		// Sort the appointments
		$sa = ($service->isRecurring()) 
			? $staffAppt->recur->staffAppointments->sortBy('start')
			: $staffAppt;

		if ($service->isRecurring())
		{
			$series = $staffAppt->recur->staffAppointments->sortBy('start');

			foreach ($series as $s)
			{
				foreach ($s->userAppointments as $ua)
				{
					$appointments[$ua->id]['start'] = $ua->appointment->present()->start;
					$appointments[$ua->id]['due'] = $ua->present()->due;
					$appointments[$ua->id]['location'] = $ua->appointment->present()->location;
				}
			}
		}
		else
		{
			foreach ($staffAppt->userAppointments as $ua)
			{
				$appointments[$ua->id]['start'] = $ua->appointment->present()->start;
				$appointments[$ua->id]['due'] = $ua->present()->due;
				$appointments[$ua->id]['location'] = $ua->appointment->present()->location;
			}
		}

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
			'appointments'	=> $appointments,
			'location'		=> $staffAppt->present()->location,
		);

		// Email the attendees
		Mail::send('emails.bookedLesson', $data, function($msg) use ($user, $service)
		{
			$msg->to($user->email)
				->subject(Config::get('bjga.email.subject')." {$service->name} Booked")
				->replyTo($service->staff->user->email);
		});

		// Email the instructor
		Mail::send('emails.bookedLessonInstructor', $data, function($msg) use ($service, $staffAppt)
		{
			$msg->to($service->staff->user->email)
				->subject(Config::get('bjga.email.subject')." {$service->name} Booking Notification")
				->replyTo($staffAppt->userAppointments->first()->user->email);
		});
	}

	public function createProgram($service, $userAppt)
	{
		// Set the data
		$data = array(
			'service'	=> $service->name,
			'schedule'	=> array(),
			'location'	=> $service->present()->location,
		);

		$occurrences = $service->serviceOccurrences->sortBy(function($s){ return $s->start; });

		foreach ($occurrences as $o)
		{
			$data['schedule'][] = array(
				'start'	=> $o->start->format('l F jS, Y, g:ia'),
				'end'	=> $o->end->format('g:ia'),
			);
		}

		// Get a count of the occurences in the service
		$data['occurrences'] = $occurrences->count();

		// Get the user
		$user = $userAppt->user;

		// Email the attendees
		Mail::send('emails.bookedProgram', $data, function($msg) use ($user, $service)
		{
			$msg->to($user->email)
				->subject(Config::get('bjga.email.subject')." {$service->name} Enrollment")
				->replyTo($service->staff->user->email);
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
		Mail::send('emails.studentCancelled', $data, function($message) use ($emails, $service, $user)
		{
			$message->to($emails)
				->subject(Config::get('bjga.email.subject')." {$service->name} - Student Cancellation")
				->replyTo($user->email);
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
		Mail::send('emails.instructorCancelled', $data, function($message) use ($emails, $service)
		{
			$message->to($emails)
				->subject(Config::get('bjga.email.subject')." {$service->name} Schedule Change")
				->replyTo($service->staff->user->email);
		});

		// Update the calendar
		Queue::push('Scheduler\Services\CalendarService', array('staff' => $staffAppt->staff->id));
	}

}