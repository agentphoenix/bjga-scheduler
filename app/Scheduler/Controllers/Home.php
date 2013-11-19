<?php namespace Scheduler\Controllers;

use Auth;
use Hash;
use View;
use Input;
use Session;
use Password;
use Redirect;
use Validator;
use UserRepositoryInterface;
use ServiceRepositoryInterface;
use AppointmentRepositoryInterface;

class Home extends Base {

	public function __construct(UserRepositoryInterface $user,
			AppointmentRepositoryInterface $appointment,
			ServiceRepositoryInterface $service)
	{
		$this->user = $user;
		$this->service = $service;
		$this->appointment = $appointment;
	}

	/**
	 * Home page.
	 */
	public function getIndex()
	{
		return View::make('pages.index')
			->with('myEvents', $this->user->getUserSchedule())
			->with('upcomingEvents', $this->appointment->getUpcomingEvents());
	}

	/**
	 * Do the log in process.
	 */
	public function postLogin()
	{
		// Setup the validator
		$validator = Validator::make(Input::all(), array(
			'email'		=> 'required',
			'password'	=> 'required',
		));

		// Validate the data
		if ( ! $validator->passes())
		{
			// Flash the session
			Session::flash('loginMessage', "Your information couldn't be validated. Please correct the issues and try again.");

			return Redirect::back()->withInput()->withErrors($validator->errors());
		}

		// Get the values from the POST
		$email = e(trim(Input::get('email')));
		$password = e(trim(Input::get('password')));

		// Do the login
		if (Auth::attempt(array('email' => $email, 'password' => $password), true))
		{
			if (Auth::user()->isStaff())
			{
				return Redirect::route('admin');
			}
			else
			{
				return Redirect::route('home');
			}
		}

		// Flash the session
		Session::flash('loginMessage', "Either your email address or password were incorrect. Please try again.");

		return Redirect::back()->withInput();
	}

	/**
	 * Log a user out.
	 */
	public function getLogout()
	{
		Auth::logout();
		
		return Redirect::route('home');
	}

	/**
	 * Register a new user.
	 */
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
			// Log the user in
			Auth::login($user, true);

			# TODO: send an email to the user with their information

			// Kick them over to the home page
			return Redirect::route('home');
		}
		else
		{
			// Flash the session
			Session::flash('registerMessage', "There was an error creating your account. Please try again!");

			return Redirect::back()->withInput();
		}
	}

	public function getPasswordReminder()
	{
		return View::make('pages.passwordReminder');
	}
	public function postPasswordReminder()
	{
		return Password::remind(array('email' => Input::get('email')));
	}
	public function getPasswordReset($token)
	{
		return View::make('pages.passwordReset')->with('token', $token);
	}
	public function postPasswordReset($token)
	{
		$credentials = array(
			'email' => Input::get('email'),
			'password' => Input::get('password'),
			'password_confirmation' => Input::get('password_confirmation')
		);

		return Password::reset($credentials, function($user, $password)
		{
			$user->password = $password;
			$user->save();

			return Redirect::route('home')
				->with('message', "Your password has been reset!")
				->with('messageStatus', 'success');
		});
	}

	/**
	 * Show all events.
	 */
	public function getAllEvents()
	{
		return View::make('pages.events')
			->with('events', $this->appointment->getUpcomingEvents(0));
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
			->with('event', $event)
			->with('appointment', $appointment)
			->with('currentUser', Auth::user());
	}

}