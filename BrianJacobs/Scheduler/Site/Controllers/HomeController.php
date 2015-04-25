<?php namespace Scheduler\Controllers;

use App,
	Auth,
	Date,
	Mail,
	View,
	Event,
	Input,
	Config,
	Session,
	Redirect,
	Validator,
	UserRepositoryInterface,
	StaffRepositoryInterface,
	ServiceRepositoryInterface,
	LocationRepositoryInterface,
	StaffAppointmentRepositoryInterface;

class HomeController extends BaseController {

	protected $user;
	protected $staff;
	protected $service;
	protected $appointment;
	protected $locations;

	public function __construct(UserRepositoryInterface $user,
			StaffAppointmentRepositoryInterface $appointment,
			ServiceRepositoryInterface $service,
			LocationRepositoryInterface $locations,
			StaffRepositoryInterface $staff)
	{
		parent::__construct();

		$this->user = $user;
		$this->staff = $staff;
		$this->service = $service;
		$this->appointment = $appointment;
		$this->locations = $locations;
	}

	public function mySchedule($days = 10)
	{
		if (Auth::check())
		{
			// Staff should only show 10 days
			$days = ($this->currentUser->isStaff()) ? $days : false;

			return View::make('pages.schedule')
				->withSchedule($this->user->getSchedule($this->currentUser, $days))
				->withNow(\Date::now());
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
			if (Session::has('url.intended'))
			{
				return Redirect::intended('home');
			}
			else
			{
				return Redirect::route('home');
			}
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
		), array(
			'email.unique'		=> "The email address you entered is already registered. You can <a href='".\URL::route('home')."'>log in</a>, or, if you've forgotten your password, you can reset it from the <a href='".\URL::to('password/remind')."'>Reset Password</a> page.",
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
				->with('message', "Welcome to the Brian Jacobs Golf scheduler! An email has been sent with the log in information you entered during registration. If you don't see the email, make sure to check your spam folder. From the scheduler, you can book lessons with a Brian Jacobs Golf instructor and enroll in any of our programs. Get started today by booking a lesson or enrolling in a program!")
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
			->withEvents($this->appointment->getUpcomingEvents(0))
			->withMonths(array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'));
	}

	/**
	 * Show a specific event.
	 */
	public function getEvent($slug = false)
	{
		if ( ! $slug)
		{
			return View::make('pages.admin.error')
				->withError("Please specify an event to get more information about. If you believe you've received this message in error, please use the Report a Problem link from the sidebar and let us know how you received this message.");
		}

		// Get the event
		$event = $this->service->findBySlug($slug);

		if ($event)
		{
			// Get the staff appointment record
			$appointment = $event->appointments->first();

			return View::make('pages.event')
				->withEvent($event)
				->withAppointment($appointment);
		}

		return View::make('pages.admin.error')
			->withError("We couldn't find the event you're looking for. Please try again. If you believe you've received this in error, please contact us at ". Config::get('bjga.email.adminAddress') .".");
	}

	public function report()
	{
		// Get the user
		$user = $this->currentUser;
		
		// Set the data
		$data = array(
			'user'		=> $user->name,
			'content'	=> Input::get('content'),
		);

		// Send the email
		Mail::send('emails.reportProblem', $data, function($msg) use ($user)
		{
			$msg->to(Config::get('bjga.email.adminAddress'))
				->from($user->email, $user->name)
				->subject(Config::get('bjga.email.subject').' Scheduler Problem Report');
		});

		return Redirect::route('home')
			->with('message', "Thanks for your feedback. We'll begin looking into the issue and contact you if we need more information.")
			->with('messageStatus', 'success');
	}

	public function applyCredit()
	{
		// Get an instance of the credits repo
		$credits = App::make('CreditRepository');

		// Find the code
		$item = $credits->findByCode(Input::get('code'));

		// Setup the return
		$return = [
			'code'		=> 0,
			'message'	=> 'An error occurred.',
		];

		if ($item)
		{
			if ((int) $item->user_id === 0 and empty($item->email))
			{
				$updateData['user_id'] = $this->currentUser->id;

				if ($item->type == 'time')
				{
					$updateData['expires'] = \Date::now()->addDay()->addYear()->startOfDay();
				}

				// Update the credit
				$credits->update($item->id, $updateData);

				// Set a successful return code
				$return['code'] = 1;
			}
			else
			{
				// Set the flash message
				$return['message'] = "The user credit code you've entered is already in use and cannot be applied to your account. Please contact Brian Jacobs Golf for further help.";
			}
		}
		else
		{
			// Set the flash message
			$return['message'] = "User credit code not found!";
		}

		return json_encode($return);
	}

	public function studentHistory()
	{
		if (Auth::check())
		{
			// Get the current user
			$user = $this->currentUser;

			return View::make('pages.admin.appointments.usersHistory')
				->withUser($user)
				->withHistory($this->user->getScheduleHistory($user, 'desc'));
		}

		return Redirect::route('login');
	}

	public function locations()
	{
		return View::make('pages.locations')
			->withLocations($this->locations->all());
	}

	public function search()
	{
		$instructors = [0 => "All Instructors"] + $this->staff->allForDropdown();

		$lessons = [
			60	=> "Private Lesson (60 minutes)",
			90	=> "Club Evaluation/Fitting (90 minutes)",
			180	=> "9-Hole Playing Lesson (3 hours)",
		];

		$timeframe = [
			'today'		=> "Today",
			'tomorrow'	=> "Tomorrow",
			'week1'		=> "Next 7 Days",
			'week2'		=> "Next 14 Days",
		];

		return View::make('pages.search', compact('instructors', 'lessons', 'timeframe'));
	}

	public function doSearch()
	{
		$results = true;

		// Grab the availability class
		$avCheck = App::make('scheduler.availability');

		// Get the duration
		$duration = Input::get('duration');

		// Get the instructor(s)
		$searchInstructor = (Input::get('instructor') > 0)
			? $this->staff->find(Input::get('instructor'))
			: $this->staff->all(true);

		switch (Input::get('timeframe'))
		{
			case 'today':
				if (Input::get('instructor') > 0)
				{
					$header = $searchInstructor->user->present()->name;
					$availability = $avCheck->today($searchInstructor, $duration);
				}
				else
				{
					$allStaffAvailability = [];

					foreach ($searchInstructor as $staff)
					{
						$allStaffAvailability[] = [
							'staff'	=> $staff->user->present()->name,
							'times'	=> $avCheck->today($staff, $duration),
						];
					}
				}
			break;

			case 'tomorrow':
				if (Input::get('instructor') > 0)
				{
					$header = $searchInstructor->user->present()->name;
					$availability = $avCheck->tomorrow($searchInstructor, $duration);
				}
				else
				{
					$allStaffAvailability = [];

					foreach ($searchInstructor as $staff)
					{
						$allStaffAvailability[] = [
							'staff'	=> $staff->user->present()->name,
							'times'	=> $avCheck->tomorrow($staff, $duration),
						];
					}
				}
			break;

			case 'week1':
				if (Input::get('instructor') > 0)
				{
					$header = $searchInstructor->user->present()->name;
					$availability = $avCheck->week(1, $searchInstructor, $duration);
				}
				else
				{
					$allStaffAvailability = [];

					foreach ($searchInstructor as $staff)
					{
						$allStaffAvailability[] = [
							'staff'	=> $staff->user->present()->name,
							'times'	=> $avCheck->week(1, $staff, $duration),
						];
					}
				}
			break;

			case 'week2':
				if (Input::get('instructor') > 0)
				{
					$header = $searchInstructor->user->present()->name;
					$availability = $avCheck->week(2, $searchInstructor, $duration);
				}
				else
				{
					$allStaffAvailability = [];

					foreach ($searchInstructor as $staff)
					{
						$allStaffAvailability[] = [
							'staff'	=> $staff->user->present()->name,
							'times'	=> $avCheck->week(2, $staff, $duration),
						];
					}
				}
			break;
		}

		$instructors = [0 => "All Instructors"] + $this->staff->allForDropdown();

		$lessons = [
			60	=> "Private Lesson (60 minutes)",
			90	=> "Club Evaluation/Fitting (90 minutes)",
			180	=> "9-Hole Playing Lesson (3 hours)",
		];

		$timeframe = [
			'today'		=> "Today",
			'tomorrow'	=> "Tomorrow",
			'week1'		=> "Next 7 Days",
			'week2'		=> "Next 14 Days",
		];

		$now = Date::now();

		return View::make('pages.search', compact('instructors', 'lessons', 'timeframe', 'availability', 'header', 'now', 'results', 'allStaffAvailability'));
	}

}