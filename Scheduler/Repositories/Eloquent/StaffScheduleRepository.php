<?php namespace Scheduler\Repositories\Eloquent;

use Date,
	StaffModel,
	StaffScheduleModel,
	StaffScheduleRepositoryInterface;

class StaffScheduleRepository implements StaffScheduleRepositoryInterface {

	protected $schedule;
	protected $service;
	protected $staff;
	protected $date;
	protected $leadout = 15;

	/**
	 * Get a staff member's available time.
	 *
	 * @param	int			$staffId	Staff member ID
	 * @param	Carbon		$date		Date object
	 * @param	Service		$service	Service object
	 * @param	array
	 */
	public function getAvailability($staffId, $date, $service)
	{
		// Set the date and service
		$this->date = $date;
		$this->service = $service;

		// Get the staff member we're booking for
		$this->staff = StaffModel::find($staffId);

		// Get the staff member's schedule for the day of the week
		$this->schedule = StaffScheduleModel::where('staff_id', $staffId)
			->where('day', $this->date->dayOfWeek)
			->first();

		// If the staff member has availability for today
		if ($this->schedule)
		{
			// Create the normal schecule as a starting point to trim from
			$totalAvailability = $this->createNormalSchedule();

			// Now, trim any appointments from the staff member
			$this->trimAppointments($totalAvailability);

			return $totalAvailability;
		}

		return false;
	}

	/**
	 * Find the available time blocks for a staff member.
	 *
	 * @param	array	$availability	The staff member's availability
	 * @param	Service	$service		Service object
	 * @return	array
	 */
	public function findTimeBlock($availability, $service)
	{
		// All services have a 15 minute lead out time
		$totalDuration = $service->duration + $this->leadout;

		// We work in 15 minute blocks, so figure out how many blocks that is
		$timeBlocks = $totalDuration / 15;

		// Loop through the availability and figure out if we have enough
		// consecutive time to book this service for this staff member
		foreach ($availability as $key => $av)
		{
			// Get a slice of the array
			$slice = array_slice($availability, $key, $timeBlocks, true);

			$freshSlice = array_diff($slice, array(false));

			// Get the keys
			$sliceKeys = array_keys($freshSlice);

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
		if ( ! empty($this->schedule->availability))
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

		return array();
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
		$appointments = $this->staff->appointments->filter(function($a) use ($date)
		{
			return $a->start->toDateString() == $date->toDateString();
		});

		if ($appointments->count() > 0)
		{
			foreach ($appointments as $appt)
			{
				// Set the date objects for calculated time
				$calcTime = $appt->start->copy();

				while ($calcTime->gte($appt->start) and $calcTime->lte($appt->end))
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

}