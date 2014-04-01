<?php namespace Scheduler\Services;

use App,
	File,
	StaffModel;
use DateTime, DateTimeZone;
use Sabre\VObject\Component\VEvent,
	Sabre\VObject\Component\VCalendar;

class CalendarService {

	public function fire($job, $data)
	{
		// Get the staff member
		$staff = StaffModel::find($data['staff']);

		// Set the calendar we're using
		$calendarName = str_replace(' ', '', $staff->user->name).'.ics';

		// Get the calendar
		$calendar = new VCalendar;

		// Create a new event
		$event = array();

		foreach ($staff->appointments as $a)
		{
			// Set the summary
			$event['SUMMARY'] = ($a->service->isLesson())
				? "{$a->userAppointments->first()->user->name} ({$a->service->name})"
				: $a->service->name;

			// Set the start time and end time
			$event['DTSTART'] = $a->start;
			$event['DTEND'] = $a->end;

			// Add the event to the calendar
			$calendar->add('VEVENT', $event);
		}

		// Write the new output to the file
		File::put(App::make('path.public')."/calendars/{$calendarName}", $calendar->serialize());

		// Delete the job from the queue
		$job->delete();
	}

}