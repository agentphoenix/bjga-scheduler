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
		// Get the services for this category
		$services = array_merge(array('' => "Please choose one"), $this->service->getValues('lesson'));

		return View::make('pages.booking.lesson')
			->with('services', $services);
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
		$services = array_merge(array('' => "Please choose one"), $this->service->getValues('program'));

		return View::make('pages.booking.program')
			->with('services', $services);
	}
	public function postProgram()
	{
		Book::program(Input::all());

		return Redirect::route('home')
			->with('message', "You've successfully enrolled in the program.")
			->with('messageStatus', 'success');
	}

}