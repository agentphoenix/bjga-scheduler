<?php namespace Scheduler\Services;

use Date,
	ServiceModel,
	StaffAppointmentModel;

class AvailabilityService {

	protected $date;
	protected $user;
	protected $service;
	protected $schedule;

	public function find($user, $date, $service)
	{
		$this->date = $date;
		$this->user = $user;
		$this->service = $service;
		$this->schedule = $user->staff->schedule->filter(function($d) use ($date)
		{
			return (int) $d->day === (int) date('w', $date->format('U'));
		});

		// If the staff member has availability for this day
		if ($this->schedule)
		{
			// Create the normal schecule as a starting point to trim from
			$totalAvailability = $this->createNormalSchedule();

			s($totalAvailability);

			// Now, trim any appointments from the staff member
			$this->trimAppointments($totalAvailability);

			return $totalAvailability;
		}

		return false;
	}

	public function today(){}

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
		$appointments = $this->user->staff->appointments->filter(function($a) use ($date)
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