<?php namespace Scheduler\Controllers;

use Date;
use View;
use Input;
use Service;
use Appointment;
use ServiceFullException;
use UserRepositoryInterface;
use ServiceRepositoryInterface;
use ScheduleRepositoryInterface;

class Booking extends Base {

	public function __construct(UserRepositoryInterface $user,
			ScheduleRepositoryInterface $schedule,
			ServiceRepositoryInterface $service)
	{
		$this->user = $user;
		$this->schedule = $schedule;
		$this->service = $service;
	}

	public function getIndex()
	{
		return View::make('pages.book.index');
	}

	public function getLesson()
	{
		// Get the services for this category
		$services = array(0 => "Please choose one") + $this->service->getValues(1);

		return View::make('pages.book.lesson')
			->with('services', $services);
	}
	public function postLesson()
	{
		// Get the items from the POST
		$serviceID = e(Input::get('service_id'));

		// Get the user
		//$user = Sentry::getUser();

		// Get the service
		$service = Service::find($serviceID);

		// Get the date
		$date = Date::createFromFormat('Y-m-d', e(Input::get('date')));

		try
		{
			// Create a new appointment
			$appt = Appointment::create(array(
				'staff_id'		=> $service->staff->id,
				'service_id'	=> $service->id,
				'date'			=> $date->toDateString(),
				'start_time'	=> $date->toTimeString(),
				'end_time'		=> $date->copy()->addMinutes($service->duration)->addMinutes(15)->toTimeString(),
			));

			// Attach the user to the appointment
			$appt->attendees()->attach($user->id);
		}
		catch (ServiceFullException $e)
		{
			// This service is full and cannot accept any new attendees.
		}

		return Redirect::to('book/lesson');
	}

}