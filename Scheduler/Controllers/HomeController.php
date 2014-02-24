<?php namespace Scheduler\Controllers;

use Auth,
	Mail,
	View,
	Event,
	Input,
	Config,
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

	public function register()
	{
		// Generate a random number
		$random = mt_rand(1, 99);

		// Put the number into the session
		Session::flash('confirmNumber', $random);

		return View::make('pages.register')->with('confirmNumber', $random);
	}
	public function doRegistration()
	{
		// Setup the validator
		$validator = Validator::make(Input::all(), array(
			'name'				=> 'required',
			'email'				=> 'required|email|unique:users,email',
			'password'			=> 'required',
			'password_confirm'	=> 'required|same:password',
			'phone'				=> 'required',
			'confirm'			=> 'required'
		));

		// Validator failed
		if ( ! $validator->passes())
		{
			// Flash the session
			Session::flash('registerMessage', "Your information couldn't be validated. Please correct the issues and try again.");

			return Redirect::route('register')
				->withInput()
				->withErrors($validator->errors());
		}

		// Make sure the confirmation number matches
		if (Input::get('confirm') != Session::get('confirmNumber'))
		{
			return Redirect::route('register')
				->with('message', "Registration failed due to incorrect anti-spam confirmation number.")
				->with('messageStatus', 'danger');
		}

		// Create the user
		$user = $this->user->create(Input::all());

		if ($user)
		{
			// Log the user in
			Auth::login($user, true);

			// Fire the registration event
			Event::fire('user.registered', array($user, Input::all()));

			return Redirect::route('home')
				->with('message', "Welcome to the Brian Jacobs Golf scheduler! From here, you can book lessons with a Brian Jacobs Golf instructor and enroll in any of our programs. Get started today by booking a lesson or joining a program.")
				->with('messageStatus', 'success');
		}
		else
		{
			return Redirect::route('register')
				->withInput()
				->with('registerMessage', "There was an error creating your account. Please try again!");
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

	public function report()
	{
		// Get the user
		$user = $this->currentUser;

		// Set the data
		$data = array(
			'user'		=> $this->currentUser,
			'content'	=> Input::get('content'),
		);

		// Send the email
		Mail::queue('emails.system.reportProblem', $data, function($msg) use ($user)
		{
			$msg->to('david.vanscott@gmail.com', 'David VanScott')
				->from($user->email, $user->name)
				->subject(Config::get('bjga.email.subject').' Scheduler Problem Report');
		});

		return Redirect::route('home')
			->with('message', "Thanks for your feedback. We'll begin looking into the issue and contact you if we need more information.")
			->with('messageStatus', 'success');
	}

}