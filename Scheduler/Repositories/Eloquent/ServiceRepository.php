<?php namespace Scheduler\Repositories\Eloquent;

use Date,
	Queue,
	ServiceModel,
	UserAppointmentModel,
	StaffAppointmentModel,
	ServiceOccurrenceModel,
	ServiceRepositoryInterface;
use Illuminate\Support\Collection;

class ServiceRepository implements ServiceRepositoryInterface {

	/**
	 * Get all services.
	 *
	 * @return	Collection
	 */
	public function all()
	{
		return ServiceModel::all();
	}

	/**
	 * Get all the services broken down by category.
	 *
	 * @return	array
	 */
	public function allByCategory()
	{
		// Get everything from the database
		$items = $this->all();

		// Start a holding array
		$final = array();

		if ($items->count() > 0)
		{
			$final = array('lesson' => array(), 'program' => array());

			foreach ($items as $item)
			{
				$final[$item->category][] = $item;
			}
		}

		return $final;
	}

	/**
	 * Get all the services by category for a dropdown.
	 *
	 * @return	array
	 */
	public function allForDropdownByCategory()
	{
		// Get all the services
		$all = $this->all();

		// Start a holding array
		$services = array();

		if ($all->count() > 0)
		{
			foreach ($all as $a)
			{
				$services[$a->category][$a->id] = $a->name;
			}
		}

		return $services;
	}

	/**
	 * Get all program services for a given timeframe.
	 *
	 * @param	int		$timeframe	The timeframe for programs in days
	 * @return	Collection
	 */
	public function allPrograms($timeframe = false)
	{
		// Get the services
		$services = ServiceModel::getCategory('program')->get();

		if ($timeframe)
		{
			// Filter by the timeframe
			return $services->filter(function($s) use ($timeframe)
			{
				if ($s->appointments)
				{
					$startDate = $s->appointments->first()->start;
					$endDate = $startDate->copy()->addDays($timeframe)->endOfDay();

					return $startDate->lte($endDate);
				}
			});
		}

		return $services;
	}

	/**
	 * Create a new service.
	 *
	 * In addition to creating the service, this will also create the service
	 * occurrence records and create the staff appointment records as well.
	 *
	 * @param	array	$data	Data to use for creation
	 * @return	ServiceModel
	 */
	public function create(array $data)
	{
		// Create the service
		$service = ServiceModel::create($data);

		if (array_key_exists('service_dates', $data))
		{
			$i = 0;

			foreach ($data['service_dates'] as $key => $date)
			{
				if ( ! empty($date))
				{
					$start = Date::createFromFormat('Y-m-d', $date, 'America/New_York');
					$start->hour(substr($data['service_times_start'][$key], 0, 2))
						->minute(substr($data['service_times_start'][$key], 3, 2))
						->second(0);

					$end = Date::createFromFormat('Y-m-d', $date, 'America/New_York');
					$end->hour(substr($data['service_times_end'][$key], 0, 2))
						->minute(substr($data['service_times_end'][$key], 3, 2))
						->second(0);

					// Create the service occurrences
					$serviceOccurrence = ServiceOccurrenceModel::create(array(
						'service_id'	=> $service->id,
						'start'			=> $start,
						'end'			=> $end,
					));

					// Create the staff appointments
					$staffAppt = StaffAppointmentModel::create(array(
						'staff_id'		=> $service->staff->id,
						'service_id'	=> $service->id,
						'occurrence_id'	=> $serviceOccurrence->id,
						'start'			=> $start,
						'end'			=> $end,
					));

					++$i;
				}
			}

			// Make sure we have an accurate count of occurrences for the service record
			$service = $service->fill(array('occurrences' => $i))->save();

			// Update the calendar
			Queue::push('Scheduler\Services\CalendarService', array('model' => $service));
		}

		return $service;
	}

	/**
	 * Delete a service by its primary key and all of its associated data.
	 *
	 * @param	int		$id
	 * @return	ServiceModel
	 */
	public function delete($id)
	{
		// Get the service
		$service = $this->find($id);

		// Remove any service occurrences
		if ($service->serviceOccurrences->count() > 0)
		{
			foreach ($service->serviceOccurrences as $o)
			{
				$o->delete();
			}
		}

		// Remove any staff and user appointments
		if ($service->appointments->count() > 0)
		{
			foreach ($service->appointments as $appt)
			{
				if ($appt->userAppointments->count() > 0)
				{
					// Start an array for holding attendee email addresses
					$emailAddresses = array();

					foreach ($service->appointments->userAppointments as $ua)
					{
						// Get the email address
						$emailAddresses[] = $ua->user->email;

						// Delete the user appointment
						$ua->delete();
					}

					// Send an email to the attendees that the appointment has been canceled
				}

				// Remove the staff appointment
				$appt->delete();
			}
		}

		// Delete the service
		$service->delete();

		// Update the calendar
		Queue::push('Scheduler\Services\CalendarService', array('model' => $service));

		return $service;
	}

	/**
	 * Find a service by its primary key.
	 *
	 * @param	int		$id
	 * @return	ServiceModel
	 */
	public function find($id)
	{
		return ServiceModel::find($id);
	}

	/**
	 * Find a service by its slug.
	 *
	 * @param	string	$slug
	 * @return	ServiceModel
	 */
	public function findBySlug($slug)
	{
		return ServiceModel::where('slug', 'like', "%{$slug}%")->first();
	}

	/**
	 * Convert a collection for use in a dropdown.
	 *
	 * @param	Collection	$collection
	 * @param	string		$key
	 * @param	string		$value
	 * @return	array
	 */
	public function forDropdown(Collection $collection, $key, $value)
	{
		if ($collection->count() > 0)
			return $collection->toSimpleArray($key, $value);

		return $collection->toArray();
	}
	
	/**
	 * Get the values for services by category.
	 *
	 * @param	string	$category
	 * @return	Collection
	 */
	public function getValues($category)
	{
		// Get the category items
		$services = ServiceModel::getCategory($category)->get();

		return $this->forDropdown($services, 'id', 'name');
	}

	/**
	 * Update a service by its primary key.
	 *
	 * @param	int		$id
	 * @param	array	$data	Data for update
	 * @return	ServiceModel
	 */
	public function update($id, array $data)
	{
		// Get the service
		$service = $this->find($id);

		if ($service)
		{
			// Update the service
			$update = $service->fill($data)->save();

			// Update the service schedule if we have them
			if (array_key_exists('service_dates', $data))
			{
				$i = 0;

				foreach ($data['service_dates'] as $key => $date)
				{
					// New occurrence added
					// Existing occurence updated
					
					if ( ! empty($date))
					{
						$start = Date::createFromFormat('Y-m-d', $date, 'America/New_York');
						$start->hour(substr($data['service_times_start'][$key], 0, 2))
							->minute(substr($data['service_times_start'][$key], 3, 2))
							->second(0);

						$end = Date::createFromFormat('Y-m-d', $date, 'America/New_York');
						$end->hour(substr($data['service_times_end'][$key], 0, 2))
							->minute(substr($data['service_times_end'][$key], 3, 2))
							->second(0);

						// Get the occurrence
						$occurrence = $service->serviceOccurrences->filter(function($o) use ($key)
						{
							return $o->id == $key;
						})->first();

						if ($occurrence)
						{
							// Update the service occurrence
							$occurrence->fill(array('start' => $start, 'end' => $end))->save();

							// Update the staff appointments with this occurrence
							if ($occurrence->staffAppointments->count() > 0)
							{
								foreach ($occurrence->staffAppointments as $sa)
								{
									$sa->fill(array('start' => $start, 'end' => $end))->save();
								}
							}
						}
						else
						{
							// Create a new occurrence
							$newOccurrence = ServiceOccurrenceModel::create(array(
								'service_id' => $service->id,
								'start' => $start,
								'end' => $end
							));

							// Create a new staff appointment
							$newAppt = StaffAppointmentModel::create(array(
								'staff_id'		=> $service->staff->id,
								'service_id'	=> $service->id,
								'occurrence_id'	=> $newOccurrence->id,
								'start'			=> $start,
								'end'			=> $end,
							));

							// Get the attendees for the service
							$attendees = $service->attendees();

							if ($attendees->count() > 0)
							{
								$baseUserAppt = array(
									'appointment_id'	=> $newAppt->id,
									'occurrence_id'		=> $newOccurrence->id,
								);

								foreach ($attendees as $attendee)
								{
									// Add the user ID in
									$userAppt = $baseUserAppt + array('user_id' => $attendee->id);

									// If it's a staff member, set them as having paid
									if ($attendee->isStaff())
										$userAppt = $userAppt + array('paid' => (int) true);

									// Create the new user appointment
									UserAppointmentModel::create($userAppt);
								}
							}
						}

						++$i;
					}
				}

				// Make sure we have an accurate occurrences count
				$update = $service->fill(array('occurrences' => $i))->save();
			}

			// Update the calendar
			Queue::push('Scheduler\Services\CalendarService', array('model' => $service));

			# TODO: send emails to the attendees that the service has been changed

			return $service;
		}

		return false;
	}

}