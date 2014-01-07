<?php namespace Scheduler\Repositories\Eloquent;

use ServiceModel,
	StaffAppointmentModel,
	ServiceOccurrenceModel,
	ServiceRepositoryInterface;

class ServiceRepository implements ServiceRepositoryInterface {

	public function all()
	{
		return ServiceModel::all();
	}

	public function allByCategory()
	{
		$items = $this->all();

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

	public function allForDropdownByCategory()
	{
		$all = $this->all();

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

	public function create(array $data)
	{
		$servicesArr = array();

		foreach ($data['additional_service'] as $key => $value)
		{
			if ( ! empty($value) and $value > "0")
			{
				$servicesArr[] = "{$value},{$data['additional_service_occurrences'][$key]}";
			}
		}

		$data['additional_services'] = implode(';', $servicesArr);

		$service = ServiceModel::create($data);

		if (array_key_exists('date', $data))
		{
			ServiceOccurrenceModel::create(array(
				'service_id'	=> $service->id,
				'date'			=> $data['date'],
				'start_time'	=> $data['start_time'],
				'end_time'		=> $data['end_time'],
			));
		}

		if (array_key_exists('service_dates', $data))
		{
			foreach ($data['service_dates'] as $key => $date)
			{
				ServiceOccurrenceModel::create(array(
					'service_id'	=> $service->id,
					'date'			=> $date,
					'start_time'	=> $data['service_times_start'][$key],
					'end_time'		=> $data['service_times_end'][$key],
				));
			}
		}

		return $service;
	}

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
			if ($service->appointments->attendees->count() > 0)
			{
				// Start an array for holding attendee email addresses
				$emailAddresses = array();

				foreach ($service->appointments->attendees as $attendee)
				{
					// Get the email address
					$emailAddresses[] = $attendee->user->email;

					// Delete the user appointment
					$attendee->delete();
				}

				// Send an email to the attendees that the appointment has been canceled
			}

			foreach ($service->appointments as $appt)
			{
				// Remove the staff appointment
				$appt->delete();
			}
		}

		// Delete the service
		$service->delete();

		return $service;
	}

	public function find($id)
	{
		return ServiceModel::find($id);
	}

	public function findBySlug($slug)
	{
		return ServiceModel::where('slug', 'like', "%{$slug}%")->first();
	}
	
	public function getValues($category)
	{
		return ServiceModel::getCategory($category)->get()->toSimpleArray('id', 'name');
	}

	public function update($id, array $data)
	{
		// Get the service
		$service = $this->find($id);

		if ($service)
		{
			// Parse out the additional services and prep them for update
			if (array_key_exists('additional_service', $data))
			{
				$servicesArr = array();

				foreach ($data['additional_service'] as $key => $value)
				{
					if ( ! empty($value) and $value > "0")
					{
						$servicesArr[] = "{$value},{$data['additional_service_occurrences'][$key]}";
					}
				}

				$data['additional_services'] = implode(';', $servicesArr);
			}

			// Update the service
			$update = $service->update($data);

			// Update the service occurrences if we have them
			if (array_key_exists('date', $data))
			{
				$occurrence = $service->serviceOccurrences->first();
				$occurrence->update(array(
					'date'			=> $data['date'],
					'start_time'	=> $data['start_time'],
					'end_time'		=> $data['end_time']
				));
			}

			// Update the service schedule if we have them
			if (array_key_exists('service_dates', $data))
			{
				foreach ($data['service_dates'] as $key => $date)
				{
					if ( ! empty($date))
					{
						// Get the occurrence
						$occurrence = $service->serviceOccurrences->filter(function($o) use($key)
						{
							return $o->id === $key;
						})->first();

						if ($occurrence)
						{
							$occurrence->fill(array(
								'date'			=> $date,
								'start_time'	=> $data['service_times_start'][$key],
								'end_time'		=> $data['service_times_end'][$key]
							))->save();
						}
						else
						{
							ServiceOccurrenceModel::create(array(
								'service_id'	=> $service->id,
								'date'			=> $date,
								'start_time'	=> $data['service_times_start'][$key],
								'end_time'		=> $data['service_times_end'][$key],
							));
						}
					}
				}
			}

			if ($service->isOneToMany())
			{
				// Get the appointment(s)
				$appt = StaffAppointmentModel::where('service_id', $id)
					->where('date', $data['date'])->get();

				if ($appt->count() > 0)
				{
					// Update the appointment
					$appt->update(array(
						'date'			=> $data['date'],
						'start_time'	=> $data['start_time'],
						'end_time'		=> $data['end_time']
					));

					// Start an array for holding the attendee email addresses
					$emailAddresses = array();

					foreach ($appt->attendees as $attendee)
					{
						$emailAddresses[] = $attendee->user->email;
					}

					#TODO: Send an email to the attendees about any changes to the appointment
				}
			}

			if ($service->isManyToMany())
			{
				//
			}

			if ($update)
			{
				return $service;
			}
		}

		return false;
	}

}