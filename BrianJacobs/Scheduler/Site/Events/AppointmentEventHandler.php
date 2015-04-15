<?php namespace Scheduler\Events;

use Log,
	Date,
	Mail,
	Queue,
	Config;

class AppointmentEventHandler {

	public function onCreated($service, $staffAppt, $userAppt, $sendEmail)
	{
		// Update the calendar
		Queue::push('Scheduler\Services\CalendarService', array('staff' => $staffAppt->staff->id));

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
			Mail::send('emails.appointmentCreated', $data, function($message) use ($user, $service)
			{
				\Log::info('emails.appointmentCreated');

				$message->to($user->email)
					->subject(Config::get('bjga.email.subject')." Appointment Created")
					->replyTo($service->staff->user->email);
			});
		}
	}

	public function onLocationChange($staffAppt, $userAppt)
	{
		// Update the calendar
		Queue::push('Scheduler\Services\CalendarService', array('staff' => $staffAppt->staff->id));

		// Set the data
		$data = array(
			'service'	=> $staffAppt->service->name,
			'date'		=> $staffAppt->start->format(Config::get('bjga.dates.date')),
			'start'		=> $staffAppt->start->format(Config::get('bjga.dates.time')),
			'end'		=> $staffAppt->end->format(Config::get('bjga.dates.time')),
			'location'	=> $staffAppt->location->name,
		);

		// Get the user
		$user = $userAppt->user;

		// Get the service
		$service = $staffAppt->service;

		// Email the attendees
		Mail::send('emails.appointmentLocationChange', $data, function($message) use ($user, $service)
		{
			$message->to($user->email)
				->subject(Config::get('bjga.email.subject')." Appointment Location Change")
				->replyTo($service->staff->user->email);
		});
	}
	
	public function onUpdated($staffAppt, $userAppt)
	{
		// Update the calendar
		Queue::push('Scheduler\Services\CalendarService', array('staff' => $staffAppt->staff->id));

		// Set the data
		$data = array(
			'service'	=> $staffAppt->service->name,
			'date'		=> $staffAppt->start->format(Config::get('bjga.dates.date')),
			'start'		=> $staffAppt->start->format(Config::get('bjga.dates.time')),
			'end'		=> $staffAppt->end->format(Config::get('bjga.dates.time')),
		);

		// Get the user
		$user = $userAppt->user;

		// Get the service
		$service = $staffAppt->service;

		// Get the series
		$series = ($service->isRecurring())
			? $userAppt->recur->staffAppointments->sortBy('start')->filter(function($a)
				{
					return $a->start >= Date::now();
				})
			: [$userAppt->appointment];

		foreach ($series as $s)
		{
			$output = $s->start->format(Config::get('bjga.dates.date'))." ";
			$output.= $s->start->format(Config::get('bjga.dates.time'))." - ";
			$output.= $s->end->format(Config::get('bjga.dates.time'));
			$output.= " at ".$s->present()->location;

			$data['appointments'][] = $output;
		}

		// Email the attendees
		Mail::send('emails.appointmentUpdated', $data, function($message) use ($user, $service)
		{
			//Log::info('emails.appointmentUpdated');

			$message->to($user->email)
				->subject(Config::get('bjga.email.subject')." Appointment Updated")
				->replyTo($service->staff->user->email);
		});
	}

}