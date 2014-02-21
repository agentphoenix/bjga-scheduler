<?php namespace Scheduler\Repositories\Eloquent;

use Date,
	ServiceModel,
	StaffAppointmentModel,
	StaffAppointmentRepositoryInterface;
use Illuminate\Support\Collection;

class StaffAppointmentRepository implements StaffAppointmentRepositoryInterface {

	public function getAttendees($id)
	{
		// Get the appointment
		$appointment = $this->find($id);

		if ($appointment)
			return $appointment->userAppointments;

		return new Collection;
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
					$q->where('start', '<=', $today->copy()->addDays($days));
			}))->get()->sortBy(function($x)
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

}