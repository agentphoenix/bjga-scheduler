<?php namespace Scheduler\Repositories;

use Date;
use Staff;
use Schedule;
use ScheduleRepositoryInterface;

class ScheduleRepository implements ScheduleRepositoryInterface {

	/**
	 * Staff member schedule.
	 */
	public $schedule;

	/**
	 * Service to be booked.
	 */
	public $service;

	/**
	 * Staff member to be booked.
	 */
	public $staff;
	
	/**
	 * Date to be scheduled.
	 */
	public $date;

	/**
	 * Get a staff member's available time.
	 *
	 * @param	int		Staff member ID
	 * @param	string	Event date (Y-m-d)
	 * @param	Service	Service object
	 * @param	array
	 */
	public function getAvailability($staffID, $date, $service)
	{
		// Set the date
		$this->date = $date;

		// Set the service
		$this->service = $service;

		// Set the staff member
		$this->staff = Staff::find($staffID);

		// Set the schedule
		$this->schedule = Schedule::where('staff_id', $staffID)
			->where('day', $this->date->dayOfWeek)
			->first();

		// Get the exceptions for the date
		$exceptions = $this->staff->exceptions->filter(function($e) use($date)
		{
			return $e->date == $date->format('Y-m-d');
		});

		if ($exceptions->count() > 0)
		{
			foreach ($exceptions as $e)
			{
				// Get the custom schedule
				$schedule = $e->exceptions;

				foreach ($schedule as $s)
				{
					// Break the times out
					list($hour, $minute) = explode(':', $s);

					// Build the total availability
					$totalAvailability[] = $this->date->copy()
						->hour($hour)
						->minute($minute)
						->second(0);
				}
			}
		}
		else
		{
			$totalAvailability = $this->createNormalSchedule();
		}

		if ($this->schedule)
		{
			$this->trimAppointments($totalAvailability);

			return $totalAvailability;
		}

		return false;
	}

	public function findTimeBlock($availability, $service)
	{
		// Figure out the total duration of the service
		$totalDuration = $service->duration;
		$totalDuration = ($service->lead_out > 0) 
			? $totalDuration + $service->lead_out 
			: $totalDuration;

		// We work in 15 minute blocks, so figure out how many blocks that is
		$timeBlocks = $totalDuration / 15;

		// Loop through the availability and figure out if we have enough
		// consecutive time to book this service for this staff member
		foreach ($availability as $key => $av)
		{
			// Get a slice of the array
			$slice = array_slice($availability, $key, $timeBlocks, true);

			// Get the keys
			$sliceKeys = array_keys($slice);

			if ((reset($sliceKeys) + $timeBlocks - 1) != end($sliceKeys))
			{
				$availability[$key] = false;
			}
		}

		return $availability;
	}

	/**
	 * Create a normal schedule for the day.
	 *
	 * @return	array
	 */
	protected function createNormalSchedule()
	{
		// Break the availability into an array
		list($start, $end) = explode('-', $this->schedule->availability);

		// Break the times into variables
		list($startHr, $startMin) = explode(':', trim($start));
		list($endHr, $endMin) = explode(':', trim($end));

		// Set the date objects for start, end and calculated time
		$startTime = Date::createFromTime($startHr, $startMin);
		$endTime = Date::createFromTime($endHr, $endMin);
		$calcTime = $startTime->copy();

		// Loop through from start to end and set the availability
		while ($calcTime->gte($startTime) and $calcTime->lt($endTime))
		{
			// Store the availability as Carbon objects
			$availability[] = $this->date->copy()
				->hour($calcTime->hour)
				->minute($calcTime->minute)
				->second(0);

			// Add 15 minutes
			$calcTime->addMinutes(15);
		}

		return $availability;
	}

	/**
	 * Remove time slots from the total availability.
	 *
	 * @param	string	Start time
	 * @param	string	End time
	 * @param	array	Availability array (passed by ref)
	 * @return	void
	 */
	protected function removeAvailability($start, $end, &$availability)
	{
		// Break the times into variables
		list($startHr, $startMin) = explode(':', $start);
		list($endHr, $endMin) = explode(':', $end);

		// Set the date objects for start, end and calculated time
		$startTime = Date::createFromTime($startHr, $startMin);
		$endTime = Date::createFromTime($endHr, $endMin);
		$calcTime = $startTime->copy();

		foreach ($availability as $key => $a)
		{
			if ($a->gte($startTime) and $a->lte($endTime))
			{
				$availability[$key] = false;
			}
		}
	}

	/**
	 * Trim any appointments.
	 *
	 * @param	array	Total availability (passed by reference)
	 * @return	void
	 */
	protected function trimAppointments(&$availability)
	{
		// Grab a copy of the date object
		$date = $this->date;

		$appointmentBlocks = array();

		// Get the staff member's appointments
		$appointments = $this->staff->appointments->filter(function($a) use($date)
		{
			return $a->date == $date->format('Y-m-d');
		});

		if ($appointments->count() > 0)
		{
			foreach ($appointments as $appt)
			{
				// Break the times into variables
				list($startHr, $startMin) = explode(':', $appt->start_time);
				list($endHr, $endMin) = explode(':', $appt->end_time);

				// Set the date objects for start, end and calculated time
				$startTime = Date::createFromTime($startHr, $startMin);
				$endTime = Date::createFromTime($endHr, $endMin);
				$calcTime = $startTime->copy();

				while ($calcTime->gte($startTime) and $calcTime->lte($endTime))
				{
					// Store the availability as Carbon objects
					$appointmentBlocks[] = $this->date->copy()
						->hour($calcTime->hour)
						->minute($calcTime->minute)
						->second(0);

					// Add 15 minutes
					$calcTime->addMinutes(15);
				}
			}

			foreach ($availability as $key => $value)
			{
				if (in_array($value, $appointmentBlocks))
				{
					$availability[$key] = false;
				}
			}
		}
	}

	/**
	 * Trim the total availability based on the service being booked.
	 *
	 * @param	array	Total availability (passed by reference)
	 * @return	void
	 */
	protected function trimAvailability(&$availability)
	{
		// Add lead out to the duration if necessary
		$eventDuration = ((int) $this->service->lead_out > 0)
			? $this->service->duration + $this->service->lead_out
			: $this->service->duration;

		// Create a copy of the date and set the new end time
		$newEnd = end($availability)->copy()->subMinutes($eventDuration);

		foreach ($availability as $key => $a)
		{
			if ($a->gt($newEnd))
			{
				$availability[$key] = false;
			}
		}
	}

	/**
	 * Trim the schedule exceptions.
	 *
	 * @param	array	Total availability (passed by reference)
	 * @return	void
	 */
	protected function trimScheduleExceptions(&$availability)
	{
		// Grab a copy of the date object
		$date = $this->date;

		// Get the staff member's blocked schedule for the date
		$scheduleExceptions = $this->staff->exceptions->filter(function($e) use($date)
		{
			return $e->date == $date->format('Y-m-d');
		});

		$preCount = count($availability);

		$exceptions = array();

		foreach ($scheduleExceptions as $exc)
		{
			// Break the availability into an array
			list($start, $end) = explode('-', $exc->exceptions);

			// Break the times into variables
			list($startHr, $startMin) = explode(':', $start);
			list($endHr, $endMin) = explode(':', $end);

			// Set the date objects for start, end and calculated time
			$startTime = Date::createFromTime($startHr, $startMin);
			$endTime = Date::createFromTime($endHr, $endMin);
			$calcTime = $startTime->copy();

			// Loop through from start to end and set the availability
			while ($calcTime->gte($startTime) and $calcTime->lte($endTime))
			{
				// Store the availability as Carbon objects
				$exceptions[] = $this->date->copy()
					->hour($calcTime->hour)
					->minute($calcTime->minute)
					->second(0);

				// Add 15 minutes
				$calcTime->addMinutes(15);
			}
		}

		foreach ($availability as $key => $a)
		{
			if (in_array($a, $exceptions))
			{
				$availability[$key] = false;
			}
		}
	}

}