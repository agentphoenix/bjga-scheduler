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
			$data = array('service' => $service->toArray());

			if ($service->attendees()->count() > 0)
			{
				// Set the recipients
				$recipients = implode(',', $service->attendees()->toSimpleArray('id', 'email'));

				// Email the attendees
				Mail::queue('emails.serviceDeleted', $data, function($message) use ($recipients, $service)
				{
					$message->bcc($recipients)
						->subject(Config::get('bjga.email.subject')." {$service->name} Has Been Cancelled")
						->replyTo($service->staff->user->email);
				});
			}
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
				'name' => $service->name,
				'description' => $service->description,
				'price' => $service->present()->price,
				'schedule' => array(),
			);

			$occurrences = $service->serviceOccurrences->sortBy(function($s)
			{
				return $s->start;
			});

			foreach ($occurrences as $o)
			{
				$data['schedule'][] = array(
					'start'	=> $o->start->format('l F jS, Y, g:ia'),
					'end'	=> $o->end->format('g:ia'),
				);
			}

			if ($service->attendees()->count() > 0)
			{
				// Set the recipients
				$recipients = implode(',', $service->attendees()->toSimpleArray('id', 'email'));

				// Email the attendees
				Mail::queue('emails.serviceUpdated', $data, function($message) use ($recipients, $service)
				{
					$message->bcc($recipients)
						->subject(Config::get('bjga.email.subject')." {$service->name} Has Been Updated")
						->replyTo($service->staff->user->email);
				});
			}
		}
	}

}