<?php namespace Scheduler\Controllers;

use Date,
	View,
	Input,
	Config,
	UserRepositoryInterface,
	StaffRepositoryInterface,
	ServiceRepositoryInterface,
	ScheduleRepositoryInterface,
	AppointmentRepositoryInterface;

class Ajax extends Base {

	public function __construct(ScheduleRepositoryInterface $schedule,
			ServiceRepositoryInterface $service,
			UserRepositoryInterface $user,
			AppointmentRepositoryInterface $appointment,
			StaffRepositoryInterface $staff)
	{
		parent::__construct();

		$this->layout = 'layouts.ajax';

		$this->schedule = $schedule;
		$this->service = $service;
		$this->user = $user;
		$this->appointment = $appointment;
		$this->staff = $staff;

		$this->icons = Config::get('icons');
	}

	public function changePassword($id)
	{
		// Get the user
		$user = $this->user->find($id);

		if ($this->currentUser == $user)
		{
			return partial('common/modal_content', [
				'modalHeader'	=> "Change Password",
				'modalBody'		=> View::make('pages.ajax.changePassword')->with('user', $user),
				'modalFooter'	=> false,
			]);
		}
	}

	public function deleteScheduleException($id)
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			$exception = $this->staff->findException($id);

			if ($exception)
			{
				return partial('common/modal_content', [
					'modalHeader'	=> "Remove Schedule Exception",
					'modalBody'		=> View::make('pages.ajax.deleteScheduleException')
										->with('ex', $exception),
					'modalFooter'	=> false,
				]);
			}
		}
	}

	public function deleteService($id)
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			$service = $this->service->find($id);

			if ($service)
			{
				return partial('common/modal_content', [
					'modalHeader'	=> "Delete Service",
					'modalBody'		=> View::make('pages.ajax.deleteService')->with('service', $service),
					'modalFooter'	=> false,
				]);
			}
		}
	}

	public function deleteStaff($id)
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			$staff = $this->staff->find($id);

			if ($staff)
			{
				return partial('common/modal_content', [
					'modalHeader'	=> "Remove Staff Member",
					'modalBody'		=> View::make('pages.ajax.deleteStaff')->with('staff', $staff),
					'modalFooter'	=> false,
				]);
			}
		}
	}

	public function deleteUser($id)
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			$user = $this->user->find($id);

			if ($user)
			{
				return partial('common/modal_content', [
					'modalHeader'	=> "Delete User",
					'modalBody'		=> View::make('pages.ajax.deleteUser')->with('user', $user),
					'modalFooter'	=> false,
				]);
			}
		}
	}

	public function getAvailability()
	{
		// Get the data
		$serviceID = e(Input::get('service'));
		$date = Date::createFromFormat("Y-m-d", e(Input::get('date')));

		// Get the service
		$service = $this->service->find($serviceID);

		// Get the availability
		$availability = $this->schedule->getAvailability($service->staff->id, $date, $service);

		// Now find the available times
		$availableTime = $this->schedule->findTimeBlock($availability, $service);

		return View::make('pages.ajax.availability')
			->with('availability', $availableTime)
			->with('date', $date);
	}

	public function getService()
	{
		$serviceId = Input::get('service');

		$service = $this->service->find($serviceId);

		if ($service)
		{
			$appt = $service->appointments->last();

			return json_encode(array(
				'service' => array(
					'id'			=> (int) $service->id,
					'name'			=> (string) $service->name,
					'description'	=> (string) $service->description,
					'price'			=> (string) $service->price,
					'user_limit'	=> (int) $service->user_limit,
				),
				'appointment' => array(
					'id'			=> (int) $appt->id,
					'date'			=> (string) Date::createFromFormat('Y-m-d', $appt->date)->format('l F jS, Y'),
					'start_time'	=> (string) Date::createFromFormat('H:i:s', $appt->start_time)->format('g:ia'),
				),
				'enrolled' => (int) $service->appointments->last()->attendees->count(),
			));
		}

		return json_encode(array());
	}

	public function postEnroll()
	{
		// Get the appointment ID
		$id = (is_numeric(Input::get('appointment'))) ? Input::get('appointment') : false;

		// Get the appointment object
		$appointment = $this->appointment->find($id);

		$appointment->enroll();

		Session::flash('message', "Appointment has been successfully added.");
		Session::flash('messageStatus', "success");
	}

	public function postNewService()
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			// Get the selected type
			$type = Input::get('type');

			// Get all the services
			$services[] = 'Please choose a service';

			$allServices = $this->service->all();

			if ($allServices->count() > 0)
			{
				foreach ($allServices as $a)
				{
					$services[$a->category->name][$a->id] = $a->name;
				}
			}

			if ($type == 'OneToOne')
			{
				return View::make('pages.ajax.createServiceOneToOne')
					->with('services', $services)
					->with('_icons', $this->icons);
			}

			if ($type == 'OneToMany')
			{
				return View::make('pages.ajax.createServiceOneToMany')
					->with('services', $services)
					->with('_icons', $this->icons);
			}

			if ($type == 'ManyToMany')
			{
				return View::make('pages.ajax.createServiceManyToMany')
					->with('services', $services)
					->with('_icons', $this->icons);
			}
		}
	}

	public function postWithdraw()
	{
		// Get the user appointment ID
		$id = (is_numeric(Input::get('appointment'))) ? Input::get('appointment') : false;

		// Get the user appointment object
		$appointment = $this->user->getAppointment($id);

		$appointment->withdraw();

		Session::flash('message', "Appointment has been successfully removed.");
		Session::flash('messageStatus', "success");
	}

	public function setScheduleException($id)
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			$user = $this->user->find($id);

			if ($user)
			{
				$times = array(
					'8:00' => '8:00am', '8:15' => '8:15am', '8:30' => '8:30am', '8:45' => '8:45am',
					'9:00' => '9:00am', '9:15' => '9:15am', '9:30' => '9:30am', '9:45' => '9:45am',
					'10:00' => '10:00am', '10:15' => '10:15am', '10:30' => '10:30am', '10:45' => '10:45am',
					'11:00' => '11:00am', '11:15' => '11:15am', '11:30' => '11:30am', '11:45' => '11:45am',
					'12:00' => '12:00pm', '12:15' => '12:15pm', '12:30' => '12:30pm', '12:45' => '12:45pm',
					'13:00' => '1:00pm', '13:15' => '1:15pm', '13:30' => '1:30pm', '13:45' => '1:45pm',
					'14:00' => '2:00pm', '14:15' => '2:15pm', '14:30' => '2:30pm', '14:45' => '2:45pm',
					'15:00' => '3:00pm', '15:15' => '3:15pm', '15:30' => '3:30pm', '15:45' => '3:45pm',
					'16:00' => '4:00pm', '16:15' => '4:15pm', '16:30' => '4:30pm', '16:45' => '4:45pm',
					'17:00' => '5:00pm', '17:15' => '5:15pm', '17:30' => '5:30pm', '17:45' => '5:45pm',
					'18:00' => '6:00pm', '18:15' => '6:15pm', '18:30' => '6:30pm', '18:45' => '6:45pm',
					'19:00' => '7:00pm', '19:15' => '7:15pm', '19:30' => '7:30pm', '19:45' => '7:45pm',
					'20:00' => '8:00pm', '20:15' => '8:15pm', '20:30' => '8:30pm', '20:45' => '8:45pm',
				);

				return partial('common/modal_content', [
					'modalHeader'	=> "Set Schedule Exception",
					'modalBody'		=> View::make('pages.ajax.setScheduleException')
										->with('user', $user)
										->with('times', $times),
					'modalFooter'	=> false,
				]);
			}
		}
	}

}