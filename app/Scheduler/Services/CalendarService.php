<?php namespace Scheduler\Services;

use App;
use File;
use DateTime;
use DateTimeZone;
use Sabre\VObject\Component\VEvent;
use Sabre\VObject\Component\VCalendar;

class CalendarService {

	public function fire($job, $data)
	{
		// Get the appointment model
		$model = $data['model'];

		// Get the staff member
		$staff = $model->staff;

		// Set the calendar we're using
		$calendarName = str_replace(' ', '', $staff->user->name).'.ics';

		// Get the calendar
		$calendar = new VCalendar;

		// Create a new event
		$event = array();

		foreach ($staff->appointments as $a)
		{
			// Set the summary
			$event['SUMMARY'] = ($a->service->isOneToOne())
				? $a->attendees->first()->user->name
				: $a->service->name;

			// Set the start time and end time
			$event['DTSTART'] = new DateTime("{$a->date} {$a->start_time}", new DateTimeZone('America/New_York'));
			$event['DTEND'] = new DateTime("{$a->date} {$a->end_time}", new DateTimeZone('America/New_York'));

			// Add the event to the calendar
			$calendar->add('VEVENT', $event);
		}

		// Write the new output to the file
		File::put(App::make('path.public')."/calendars/{$calendarName}", $calendar->serialize());

		// Delete the job from the queue
		$job->delete();
	}

}