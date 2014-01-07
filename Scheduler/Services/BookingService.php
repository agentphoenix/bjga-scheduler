<?php namespace Scheduler\Services;

use Date,
	Event,
	UserAppointmentModel,
	StaffAppointmentModel,
	UserRepositoryInterface,
	StaffAppointmentRecurModel,
	ServiceRepositoryInterface;

class BookingService {

	protected $service;

	public function __construct(ServiceRepositoryInterface $service
			UserRepositoryInterface $user)
	{
		$this->user = $user;
		$this->service = $service;
	}

	public function lesson(array $data)
	{
		// Get the service
		$service = $this->service->find($data['service_id']);

		// Build the date
		$date = Date::createFromFormat('Y-m-d G:i', $data['date'].' '.$data['time']);

		// Get the user
		$user = $this->user->find((int) $data['user']);

		// Set the initial appointment record
		$apptRecord = array(
			'staff_id'		=> $service->staff->id,
			'service_id'	=> $service->id,
			'start'			=> $date->toDateTimeString(),
			'end'			=> $date->copy()->addMinutes($service->duration)->toDateTimeString(),
		);

		// Set the initial user appointment record
		$userApptRecord = array(
			'user_id'		=> $user->id,
			'has_gift'		=> (int) $data['has_gift'],
			'gift_amount'	=> ($data['has_gift'] == 1) ? (int) $data['gift_amount'] : 0,
			'amount'		=> ($data['has_gift'] == 1) ? ($service->price - $data['gift_amount']) : $service->price,
		);

		// Staff members get free lessons, so we need to take that into account
		if ($user->isStaff())
			$userApptRecord = array_merge($userApptRecord, array('paid' => (int) true, 'amount' => 0));

		// If we have multiple occurrences, we need to make sure everything is
		// created properly
		if ($service->occurrences > 1)
		{
			// Create a new recurring record
			$recurItem = StaffAppointmentRecurModel::create($apptRecord);

			// Book the staff member
			$bookApptArr = array_merge($apptRecord, array('recur_id' => $recurItem->id));
			$bookStaff = StaffAppointmentModel::create($bookApptArr);

			// Book the user
			$userApptArr = array_merge(
				$userApptRecord,
				array('appointment_id' => $bookStaff->id, 'recur_id' => $recurItem->id)
			);
			$bookUser = UserAppointmentModel::create($userApptArr);

			// Grab a copy of the start and end times so we can add days
			$newStartDate = $recurItem->start->copy();
			$newEndDate = $recurItem->end->copy();

			// Loop through and create all the appointments
			for ($i = 2; $i <= $service->occurrences; $i++)
			{
				// Create the staff appointments
				$sa = StaffAppointmentModel::create(array(
					'staff_id'		=> $service->staff->id,
					'service_id'	=> $service->id,
					'recur_id'		=> $recurItem->id,
					'start'			=> ($service->occurrences_schedule > 0) 
						? $newStartDate->addDays($service->occurrences_schedule)->toDateTimeString() : null,
					'end'			=> ($service->occurrences_schedule > 0) 
						? $newEndDate->addDays($service->occurrences_schedule)->toDateTimeString() : null,
				));

				// Create the user appointments
				$ua = UserAppointmentModel::create(array(
					'appointment_id'	=> $sa->id,
					'user_id'			=> $data['user'],
					'recur_id'			=> $recurItem->id,
					'amount'			=> $service->price,
				));
			}
		}
		else
		{
			// Book the staff appointment
			$bookStaff = StaffAppointmentModel::create($apptRecord);

			// Book the user appointment
			$userApptArr = array_merge($userApptRecord, array('appointment_id' => $bookStaff->id));
			$bookUser = UserAppointmentModel::create($userApptArr);
		}

		// Fire the lesson booking event
		Event::fire('book.create.lesson', array($service, $bookStaff, $bookUser));
	}

	protected function program($value='')
	{
		// Fire the program booking event
		Event::fire('book.create.oneToMany', array($service, $bookUser));
	}

}