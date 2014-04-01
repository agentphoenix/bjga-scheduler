<?php namespace Scheduler\Events;

use Mail,
	Queue,
	Config;

class ServiceEventHandler {

	public function onCreated($service, $input)
	{
		if ($service->isProgram())
		{
			// Update the calendar
			Queue::push('Scheduler\Services\CalendarService', array('staff' => $service->staff->id));
		}
	}

	public function onDeleted($service)
	{
		if ($service->isProgram())
		{
			// Update the calendar
			Queue::push('Scheduler\Services\CalendarService', array('staff' => $service->staff->id));

			// Set the data for the email
			$data = array('service' => $service);

			// Set the recipients
			$recipients = implode(',', $service->attendees()->toSimpleArray('id', 'email'));

			// Email the attendees
			Mail::queue('emails.serviceDeleted', $data, function($message) use ($recipients, $service)
			{
				$message->to($recipients)
					->subject(Config::get('bjga.email.subject')." {$service->name} Has Been Cancelled");
			});
		}
	}

	public function onUpdated($service, $input)
	{
		if ($service->isProgram())
		{
			// Update the calendar
			Queue::push('Scheduler\Services\CalendarService', array('staff' => $service->staff->id));

			// Set the data for the email
			$data = array(
				'service' => $service,
				'schedule' => $service->serviceOccurrences,
			);

			// Set the recipients
			$recipients = implode(',', $service->attendees()->toSimpleArray('id', 'email'));

			// Email the attendees
			Mail::queue('emails.serviceUpdated', $data, function($message) use ($recipients, $service)
			{
				$message->to($recipients)
					->subject(Config::get('bjga.email.subject')." {$service->name} Has Been Updated");
			});
		}
	}

}