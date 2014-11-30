<?php namespace Scheduler\Data\Repositories\Eloquent;

use Date,
	Event,
	ServiceModel,
	StaffAppointmentModel,
	StaffAppointmentRecurModel,
	StaffAppointmentRepositoryInterface;
use Illuminate\Support\Collection;

class StaffAppointmentRepository implements StaffAppointmentRepositoryInterface {

	public function create(array $data)
	{
		return StaffAppointmentModel::create($data);
	}

	public function getAttendees($id)
	{
		// Get the appointment
		$appointment = $this->find($id);

		if ($appointment)
			return $appointment->userAppointments;

		return new Collection;
	}

	public function getRecurringLessons($id = false)
	{
		if ($id)
		{
			return StaffAppointmentRecurModel::find($id);
		}

		return StaffAppointmentRecurModel::orderBy('id', 'desc')->get();

		return StaffAppointmentRecurModel::where('start', '>', Date::now()->startOfDay())->get();
	}
	
	/**
	 * Get upcoming events (does not include lessons).
	 *
	 * @param	int		$days	The day limit on what's returned
	 * @return	Collection
	 */
	public function getUpcomingEvents($days = 90)
	{
		// Setup the variables
		$serviceIds = array();
		$today = Date::now()->startOfDay();

		return ServiceModel::getCategory('program')
			->with(array('appointments' => function($q) use ($today, $days)
			{
				$q->where('start', '>=', $today);

				if ($days > 0)
				{
					$q->where('start', '<=', $today->copy()->addDays($days));
				}
			}))->get()->filter(function($f)
			{
				return $f->appointments->count() > 0;
			})->sortBy(function($x)
			{
				return $x->appointments->first()->start;
			});

		// Get all the services in the categories
		$services = ServiceModel::getCategory('program')->get();

		// Make sure we have services
		if ($services->count() > 0)
		{
			// Get the service IDs we're looking for
			foreach ($services as $service)
			{
				$serviceIds[] = $service->id;
			}

			// Start to grab the appointments
			$appointments = StaffAppointmentModel::whereIn('service_id', $serviceIds)
				->where('start', '>=', $today);

			// If we have a limit on the number of days, take that into account
			if ($days > 0)
				$appointments = $appointments->where('start', '<=', $today->copy()->addDays($days));

			// Get the results and return them
			return $appointments->get();
		}

		return new Collection;
	}

	public function getUpcomingEventsByMonth($days = 90)
	{
		// Get all the events
		$events = $this->getUpcomingEvents($days);

		if ($events->count() > 0)
		{
			$eventsArr = array();

			foreach ($events as $e)
			{
				$appt = $e->appointments->first();

				$eventsArr[$appt->start->format('F')][] = $e;
			}

			return $eventsArr;
		}

		return array();
	}

	/**
	 * Find an appointment.
	 *
	 * @param	int		$id		Appointment ID
	 * @return	Appointment
	 */
	public function find($id)
	{
		return StaffAppointmentModel::find($id);
	}

	public function updateRecurringLesson($id, array $data)
	{
		if ( ! empty($data['newDate']))
		{
			// Get the recur record
			$recur = StaffAppointmentRecurModel::find($id);

			// Get today
			$today = Date::now()->startOfDay();

			if ($recur)
			{
				// Get the service
				$service = $recur->staffAppointments->first()->service;

				// Make sure we're dealing with only appointments from today forward
				$series = $recur->staffAppointments->filter(function($s) use ($today)
				{
					return $s->start >= $today;
				});

				// Start building the new date
				$newDate = Date::createFromFormat('Y-m-d H:i', $data['newDate']." ".$data['newTime']);

				foreach ($series as $item)
				{
					$item->update(array(
						'start'	=> $newDate,
						'end'	=> $newDate->copy()->addMinutes($service->duration),
					));

					// Add to the new date
					$newDate->addDays($service->occurrences_schedule);
				}

				Event::fire('appointment.updated', array($item, $item->userAppointments->first()));
			}
		}

		return false;
	}

}