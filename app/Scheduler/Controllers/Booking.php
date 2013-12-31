<?php namespace Scheduler\Controllers;

use Date,
	View,
	Input,
	Service,
	Redirect,
	Appointment,
	ServiceFullException,
	UserRepositoryInterface,
	ServiceRepositoryInterface,
	ScheduleRepositoryInterface,
	Scheduler\Services\BookingService;

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
		$book = new BookingService;

		$book->book(Input::all());

		return Redirect::route('book.index')
			->with('message', 'Appointment has been successfully booked.')
			->with('messageStatus', 'success');
	}

	public function getProgram()
	{
		// Get the services for this category
		$services = array(0 => "Please choose one") + $this->service->getValues(2);

		return View::make('pages.book.program')
			->with('services', $services);
	}
	public function postProgram()
	{
		$book = new BookingService;

		$book->book(Input::all());

		return Redirect::route('book.index')
			->with('message', "You've successfully enrolled in the program.")
			->with('messageStatus', 'success');
	}

}