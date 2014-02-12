<?php namespace Scheduler\Controllers;

use Book,
	View,
	Input,
	Redirect,
	ServiceRepositoryInterface;

class BookingController extends BaseController {

	public function __construct(ServiceRepositoryInterface $service)
	{
		$this->service = $service;
	}

	public function getLesson()
	{
		return View::make('pages.booking.lesson')
			->withServices(array('' => "Please choose one") + $this->service->getValues('lesson', true));
	}
	public function postLesson()
	{
		Book::lesson(Input::all());

		return Redirect::route('home')
			->with('message', 'Your appointment has been booked!')
			->with('messageStatus', 'success');
	}

	public function getProgram()
	{
		// Get the services for this category
		$services = $this->service->allPrograms(90, true);

		$allServices = array('' => "Please choose one") + $this->service->forDropdown($services, 'id', 'name');

		return View::make('pages.booking.program')
			->withServices($allServices);
	}
	public function postProgram()
	{
		Book::program(Input::all());

		return Redirect::route('home')
			->with('message', "You've successfully enrolled in the program.")
			->with('messageStatus', 'success');
	}

}