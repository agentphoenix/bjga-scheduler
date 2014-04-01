<?php namespace Scheduler\Services;

use App,
	Date,
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

		// Get a subset of the staff member's appointments
		$series = $staff->appointments->filter(function($a)
		{
			// Get 14 days prior
			$targetDate = Date::now()->subDays(14)->startOfDay();

			return $a->start >= $targetDate;
		});

		foreach ($series as $a)
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