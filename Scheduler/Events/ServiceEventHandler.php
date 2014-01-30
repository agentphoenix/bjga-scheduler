<?php namespace Scheduler\Events;

use Mail, Queue;

class ServiceEventHandler {

	public function onCreated($service, $input)
	{
		if ($service->isProgram())
		{
			// Update the calendar
			Queue::push('Scheduler\Services\CalendarService', array('model' => $service));
		}
	}

	public function onDeleted($service)
	{
		if ($service->isProgram())
		{
			// Update the calendar
			Queue::push('Scheduler\Services\CalendarService', array('model' => $service));

			// Set the data for the email
			$data = array(
				'service' => $service
			);

			// Set the recipients
			$recipients = implode(',', $service->attendees()->toSimpleArray('id', 'email'));

			// Email the attendees
			Mail::queue('emails.services.deleted', $data, function($message) use ($recipients, $service)
			{
				$message->to($recipients)
					->subject("[Brian Jacobs Golf] {$service->name} Has Been Cancelled");
			});
		}
	}

	public function onUpdated($service, $input)
	{
		if ($service->isProgram())
		{
			// Update the calendar
			Queue::push('Scheduler\Services\CalendarService', array('model' => $service));

			// Set the data for the email
			$data = array(
				'service' => $service,
				'schedule' => $service->serviceOccurrences,
			);

			// Set the recipients
			$recipients = implode(',', $service->attendees()->toSimpleArray('id', 'email'));

			// Email the attendees
			Mail::queue('emails.services.updated', $data, function($message) use ($recipients, $service)
			{
				$message->to($recipients)
					->subject("[Brian Jacobs Golf] {$service->name} Has Been Updated");
			});
		}
	}

}