<?php namespace Scheduler\Services;

use Date,
	StaffModel as Staff;

class AvailabilityService {

	protected $date;
	protected $staff;
	protected $duration;
	protected $schedule;
	protected $availability;

	public function today(Staff $staff, $duration)
	{
		return $this->find($staff, Date::now()->startOfDay(), $duration);
	}

	public function tomorrow(Staff $staff, $duration)
	{
		return $this->find($staff, Date::now()->addDay()->startOfDay(), $duration);
	}

	public function week($weeks, Staff $staff, $duration)
	{
		$days = [];

		for ($d = 0; $d < $weeks * 7; $d++)
		{
			// Build the date
			$date = Date::now()->addDays($d)->startOfDay();

			// Grab the schedule for the date
			$days[] = [
				'date'	=> $date,
				'times'	=> $this->find($staff, $date, $duration)
			];
		}

		return $days;
	}

	public function find(Staff $staff, Date $date, $duration)
	{
		$this->reset();

		$this->date = $date;
		$this->staff = $staff;
		$this->duration = $duration;
		$this->schedule = $staff->schedule->filter(function($d) use ($date)
		{
			return (int) $d->day === (int) date('w', $date->format('U'));
		})->first();

		// If the staff member has availability for this day
		if ($this->schedule)
		{
			// Create the normal schecule as a starting point to trim from
			$this->createNormalSchedule();

			// Now, trim any appointments from the staff member
			$this->trimAppointments();

			// Get the time blocks that fit for the duration
			$this->findTimeBlock();

			return $this->availability;
		}

		return false;
	}

	protected function reset()
	{
		$this->date = null;
		$this->staff = null;
		$this->duration = null;
		$this->schedule = null;
		$this->availability = [];
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
				$this->availability[] = $this->date->copy()
					->hour($calcTime->hour)
					->minute($calcTime->minute)
					->second(0);

				// Add 15 minutes
				$calcTime->addMinutes(15);
			}
		}
	}

	/**
	 * Trim any appointments.
	 *
	 * @param	array	Total availability (passed by reference)
	 * @return	void
	 */
	protected function trimAppointments()
	{
		// Grab a copy of the date object
		$date = $this->date;

		$appointmentBlocks = [];

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

			foreach ($this->availability as $key => $value)
			{
				if (in_array($value, $appointmentBlocks))
				{
					$this->availability[$key] = false;
				}
			}
		}
	}

	protected function findTimeBlock()
	{
		// All services have a 15 minute lead out time
		$totalDuration = $this->duration + 15;

		// We work in 15 minute blocks, so figure out how many blocks that is
		$requiredTimeBlocks = $totalDuration / 15;

		// Loop through the availability and figure out if we have enough
		// consecutive time to book this service for this staff member
		foreach ($this->availability as $key => $av)
		{
			// Get a slice of the array
			$slice = array_slice($this->availability, $key, $requiredTimeBlocks, true);

			$freshSlice = array_diff($slice, array(false));

			// Get the keys
			$sliceKeys = array_keys($freshSlice);

			if (count($freshSlice) < $requiredTimeBlocks)
			{
				$this->availability[$key] = false;
			}
		}
	}

}
