<?php namespace Scheduler\Controllers;

use View;
use Input;
use Config;
use UserRepositoryInterface;
use StaffRepositoryInterface;
use ServiceRepositoryInterface;
use ScheduleRepositoryInterface;
use AppointmentRepositoryInterface;

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
			->with('date', $date->format('l F jS Y'));
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
				return partial('common/modal_content', [
					'modalHeader'	=> "Set Schedule Exception",
					'modalBody'		=> View::make('pages.ajax.setScheduleException')->with('user', $user),
					'modalFooter'	=> false,
				]);
			}
		}
	}

}