<?php namespace Scheduler\Services;

use App;
use File;
use DateTime;
use Sabre\VObject\Component\VEvent;
use Sabre\VObject\Component\VCalendar;

class CalendarService {

	public function createEvent($job, $data)
	{
		// Get the appointment model
		$model = $data['model'];

		// Set the calendar we're using
		$calendarName = str_replace(' ', '', $model->staff->user->name).'.ics';

		// Get the calendar
		$calendar = new VCalendar();

		// Create a new event
		$event = array();

		// Set the summary
		$event['SUMMARY'] = ($model->service->isOneToOne())
			? $model->attendees->first()->user->name
			: $model->service->name;

		// Set the start time and duration
		$event['DTSTART'] = new DateTime("{$model->date} {$model->start_time}", new DateTimeZone('America/New_York'));
		$event['DURATION'] = $model->service->duration;

		// Add the event to the calendar
		$calendar->add('VEVENT', $event);

		// Write the new output to the file
		File::put(App::make('path.public')."/calendars/{$calendarName}", $calendar->serialize());

		// Delete the job from the queue
		$job->delete();
	}

}