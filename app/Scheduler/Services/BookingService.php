<?php namespace Scheduler\Services;

use Str,
	Date,
	Event,
	Service,
	Appointment,
	UserAppointment;

class BookingService {

	protected $service;

	public function book(array $data)
	{
		// Get the service
		$this->service = Service::find($data['service_id']);

		if ($this->service->isOneToOne())
			$this->oneToOne($data);
		elseif ($this->service->isOneToMany())
			$this->oneToMany($data);
		elseif ($this->service->isManyToMany())
			$this->manyToMany($data);
	}

	protected function oneToOne(array $data)
	{
		// Build the date
		$date = Date::createFromFormat('Y-m-d G:i', $data['date'].' '.$data['time']);

		// Book the staff member
		$bookStaff = Appointment::create(array(
			'staff_id'		=> $this->service->staff->id,
			'service_id'	=> $this->service->id,
			'date'			=> $date->format('Y-m-d'),
			'start_time'	=> $date->format('G:i'),
			'end_time'		=> $date->copy()->addMinutes($this->service->duration),
		));

		// Book the user
		$bookUser = UserAppointment::create(array(
			'appointment_id'			=> $bookStaff->id,
			'user_id'					=> $data['user'],
			'gift_certificate'			=> (int) $data['gift_certificate'],
			'gift_certificate_amount'	=> ($data['gift_certificate'] == 1) ? (int) $data['gift_certificate_amount'] : 0,
			'payment_type'				=> '',
			'amount'					=> ($data['gift_certificate'] == 1) ? ($this->service->price - $data['gift_certificate_amount']) : $this->service->price,
		));

		if ($this->service->occurrences > 1)
		{
			for ($i = 2; $i <= $this->service->occurrences; $i++)
			{
				$sa = Appointment::create(array(
					'staff_id'		=> $this->service->staff->id,
					'service_id'	=> $this->service->id,
				));

				$ua = UserAppointment::create(array(
					'appointment_id'			=> $sa->id,
					'user_id'					=> $data['user'],
				));
			}
		}

		if ($this->service->additional_services !== null)
		{
			// Do we have multiple additional services?
			$multiple = Str::contains($this->service->additional_services, ';');

			if ($multiple)
			{
				// Get an array of services
				$arr = explode(';', $this->service->additional_services);

				foreach ($arr as $value)
				{
					// Make the list an array
					$servicesArr = explode(',', $value);

					// Break the items out
					list($serviceId, $numOccurrences) = $servicesArr;

					// Get the service
					$service = Service::find($serviceId);

					if ($service)
					{
						$sa = Appointment::create(array(
							'staff_id'		=> $service->staff->id,
							'service_id'	=> $service->id,
						));

						$ua = UserAppointment::create(array(
							'appointment_id'	=> $sa->id,
							'user_id'			=> $data['user'],
						));
					}
				}
			}
			else
			{
				// Make the list an array
				$servicesArr = explode(',', $this->service->additional_services);

				// Break the items out
				list($serviceId, $numOccurrences) = $servicesArr;

				// Get the service
				$service = Service::find($serviceId);

				if ($service)
				{
					$sa = Appointment::create(array(
						'staff_id'		=> $service->staff->id,
						'service_id'	=> $service->id,
					));

					$ua = UserAppointment::create(array(
						'appointment_id'	=> $sa->id,
						'user_id'			=> $data['user'],
					));
				}
			}
		}

		Event::fire('book.create.oneToOne', array($this->service, $bookStaff, $bookUser));
	}

	protected function oneToMany($value='')
	{
		Event::fire('book.create.oneToMany', array($this->service, $bookUser));
	}

	protected function manyToMany($value='')
	{
		Event::fire('book.create.manyToMany', array($this->service, $bookUser));
	}

}