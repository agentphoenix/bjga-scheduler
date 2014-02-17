<?php namespace Scheduler\Controllers;

use Book,
	View,
	Input,
	Redirect,
	ServiceRepositoryInterface;

class BookingController extends BaseController {

	public function __construct(ServiceRepositoryInterface $service)
	{
		parent::__construct();

		$this->service = $service;
	}

	public function lesson()
	{
		return View::make('pages.booking.lesson')
			->withServices(array('0' => "Please choose one") + $this->service->getValues('lesson', true));
	}
	public function storeLesson()
	{
		Book::lesson(Input::all());

		return Redirect::route('home')
			->with('message', 'Your appointment has been booked!')
			->with('messageStatus', 'success');
	}

	public function program()
	{
		// Get the services for this category
		$services = $this->service->allPrograms(90, true);

		$allServices = array('' => "Please choose one") + $this->service->forDropdown($services, 'id', 'name');

		return View::make('pages.booking.program')
			->withServices($allServices);
	}
	public function storeProgram()
	{
		Book::program(Input::all());

		return Redirect::route('home')
			->with('message', "You've successfully enrolled in the program.")
			->with('messageStatus', 'success');
	}

	public function withdraw()
	{
		Book::withdraw();
	}

	public function cancel()
	{
		if ($this->currentUser->isStaff())
		{
			// Cancel the appointment
			Book::cancel(Input::get('appointment'), Input::get('reason'));

			return Redirect::route('home')
				->with('message', "Appointment was successfully cancelled. All attendees have been notified of the cancellation.")
				->with('messageStatus', 'success');
		}
	}

}