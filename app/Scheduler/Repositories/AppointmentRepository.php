<?php namespace Scheduler\Repositories;

use Date;
use Service;
use Category;
use Appointment;
use AppointmentRepositoryInterface;

class AppointmentRepository implements AppointmentRepositoryInterface {

	/**
	 * Get upcoming events (does not include lessons).
	 *
	 * @param	int		$days	The day limit on what's returned
	 * @return	Collection
	 */
	public function getUpcomingEvents($days = 90)
	{
		// Setup the variables
		$categoryIds = array();
		$serviceIds = array();
		$date = Date::now()->startOfDay();

		// Get the categories we're looking for
		$categories = Category::where('name', 'Programs')
			->orWhere('name', 'Events')
			->get();

		// Make sure we have categories
		if ($categories->count() > 0)
		{
			foreach ($categories as $category)
			{
				$categoryIds[] = $category->id;
			}

			// Get all the services in the categories
			$services = Service::whereIn('category_id', $categoryIds)->get();

			// Make sure we have services
			if ($services->count() > 0)
			{
				foreach ($services as $service)
				{
					$serviceIds[] = $service->id;
				}

				$appointments = Appointment::whereIn('service_id', $serviceIds)
					->where('date', '>=', $date->format('Y-m-d'));

				if ($days > 0)
				{
					// Set the future date
					$futureDate = $date->copy()->addDays($days)->format('Y-m-d');

					$appointments = $appointments->where('date', '<=', $futureDate);
				}

				return $appointments->get();
			}
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
		return Appointment::find($id);
	}

}