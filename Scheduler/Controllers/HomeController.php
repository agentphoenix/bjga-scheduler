<?php namespace Scheduler\Controllers;

use Auth,
	View,
	Event,
	Input,
	Session,
	Redirect,
	Validator,
	UserRepositoryInterface,
	ServiceRepositoryInterface,
	StaffAppointmentRepositoryInterface;

class HomeController extends BaseController {

	public function __construct(UserRepositoryInterface $user,
			StaffAppointmentRepositoryInterface $appointment,
			ServiceRepositoryInterface $service)
	{
		parent::__construct();

		$this->user = $user;
		$this->service = $service;
		$this->appointment = $appointment;
	}

	public function mySchedule()
	{
		if (Auth::check())
		{
			return View::make('pages.schedule')
				->withSchedule($this->user->getSchedule($this->currentUser));
		}

		return View::make('pages.login');
	}

	public function postLogin()
	{
		$validator = Validator::make(Input::all(), array(
			'email'		=> 'required',
			'password'	=> 'required',
		));

		if ( ! $validator->passes())
		{
			Session::flash('loginMessage', "Your information couldn't be validated. Please correct the issues and try again.");

			return Redirect::back()->withInput()->withErrors($validator->errors());
		}

		$email = trim(Input::get('email'));
		$password = trim(Input::get('password'));

		if (Auth::attempt(array('email' => $email, 'password' => $password), true))
		{
			return Redirect::route('home');
		}

		Session::flash('loginMessage', "Either your email address or password were incorrect. Please try again.");

		return Redirect::back()->withInput();
	}

	public function getLogout()
	{
		Auth::logout();
		
		return Redirect::route('home');
	}

	public function getRegister()
	{
		return View::make('pages.register');
	}
	public function postRegister()
	{
		// Setup the validator
		$validator = Validator::make(Input::all(), array(
			'name'				=> 'required',
			'email'				=> 'required|email|unique:users,email',
			'password'			=> 'required',
			'password_confirm'	=> 'required|same:password',
			'phone'				=> 'required',
		));

		// Validate the data
		if ( ! $validator->passes())
		{
			// Flash the session
			Session::flash('registerMessage', "Your information couldn't be validated. Please correct the issues and try again.");

			return Redirect::back()->withInput()->withErrors($validator->errors());
		}

		// Create the user
		$user = $this->user->create(Input::all());

		if ($user)
		{
			Auth::login($user, true);

			Event::fire('scheduler.user.registered', array($user));

			return Redirect::route('home');
		}
		else
		{
			// Flash the session
			Session::flash('registerMessage', "There was an error creating your account. Please try again!");

			return Redirect::back()->withInput();
		}
	}

	/**
	 * Show all events.
	 */
	public function events()
	{
		return View::make('pages.events')
			->withEvents($this->appointment->getUpcomingEvents(0));
	}

	/**
	 * Show a specific event.
	 */
	public function getEvent($slug)
	{
		// Get the event
		$event = $this->service->findBySlug($slug);

		// Get the appointment record
		$appointment = $event->appointments->first();

		return View::make('pages.event')
			->withEvent($event)
			->withAppointment($appointment);
	}

}