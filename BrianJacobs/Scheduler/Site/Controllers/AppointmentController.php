<?php namespace Scheduler\Controllers;

use Book,
	Date,
	View,
	Event,
	Input,
	Config,
	Redirect,
	ServiceValidator,
	UserAppointmentModel,
	StaffAppointmentModel,
	UserRepositoryInterface,
	ServiceRepositoryInterface,
	LocationRepositoryInterface,
	StaffAppointmentRepositoryInterface;

class AppointmentController extends BaseController {

	protected $user;
	protected $appts;
	protected $service;
	protected $locations;

	public function __construct(ServiceRepositoryInterface $service,
			StaffAppointmentRepositoryInterface $appts,
			UserRepositoryInterface $user,
			LocationRepositoryInterface $locations)
	{
		parent::__construct();

		$this->user = $user;
		$this->appts = $appts;
		$this->service = $service;
		$this->locations = $locations;

		$this->beforeFilter(function()
		{
			if (\Auth::user() === null)
			{
				// Push the intended URL into the session
				\Session::put('url.intended', \URL::full());

				return Redirect::route('home')
					->with('message', "You must be logged in to continue.")
					->with('messageStatus', 'danger');
			}
		});
	}

	public function index()
	{
		if ($this->currentUser->isStaff())
		{
			return View::make('pages.admin.appointments.index');
		}
		else
		{
			return $this->unauthorized("You do not have permission to manage appointments!");
		}
	}

	public function create()
	{
		if ($this->currentUser->isStaff())
		{
			$services = [0 => "Please choose one"];
			$services+= $this->service->getValues('lesson', true, (int) $this->currentUser->staff->id);

			$users = ['' => "Please choose a student"];
			$users+= $this->user->getNonInstructors();

			return View::make('pages.admin.appointments.create')
				->withServices($services)
				->withUsers($users);
		}
		
		return $this->unauthorized("You do not have permission to create appointments!");
	}

	public function store()
	{
		if ($this->currentUser->isStaff())
		{
			// Do the booking
			Book::lesson(Input::all(), true, (bool) Input::get('email_student', 0));

			return Redirect::route('admin.appointment.index')
				->with('message', 'Appointment was successfully created.')
				->with('messageStatus', 'success');
		}
		else
		{
			return $this->unauthorized("You do not have permission to create appointments!");
		}
	}

	public function edit($id)
	{
		if ($this->currentUser->isStaff())
		{
			$appt = $this->appts->find($id);

			return View::make('pages.admin.appointments.edit')
				->withAppointment($appt)
				->withLocations($this->locations->listAll('id', 'name'))
				->withService($appt->service);
		}
		else
		{
			return $this->unauthorized("You do not have permission to edit appointments!");
		}
	}

	public function update($id)
	{
		if ($this->currentUser->isStaff())
		{
			// Get the staff data
			$staffData = Input::get('staff');
			$staffData['notes'] = Input::get('notes');

			// Build the dates
			$startDate = Date::createFromFormat('Y-m-d H:i', $staffData['date'].' '.$staffData['start']);
			$endDate = Date::createFromFormat('Y-m-d H:i', $staffData['date'].' '.$staffData['end']);

			// Put the dates back into the staff data
			$staffData['start'] = $startDate;
			$staffData['end'] = $endDate;

			// Clear the date
			unset($staffData['date']);
			unset($staffData['date_submit']);

			// Update the staff appointment
			$sa = StaffAppointmentModel::find(Input::get('staff_appointment_id'));
			$sa->update($staffData);

			// Update the user appointment
			$ua = UserAppointmentModel::find(Input::get('user_appointment_id'));
			$ua->update(Input::get('user'));

			// Fire the event
			Event::fire('appointment.updated', array($sa, $ua));

			return Redirect::route('admin.appointment.edit', array($id))
				->with('message', "Appointment was successfully updated.")
				->with('messageStatus', 'success');
		}
		else
		{
			return $this->unauthorized("You do not have permission to update appointments!");
		}
	}

	public function attendees($type, $id)
	{
		if ($type == 'service')
		{
			// Get the service
			$service = $this->service->find($id);

			// Get the attendees
			$attendees = $this->service->getAttendees($id);
		}
		else
		{
			// Get the attendees
			$attendees = $this->appts->getAttendees($id);

			// Get the service
			$service = ($attendees->count() > 0)
				? $attendees->first()->appointment->service
				: null;
		}

		return partial('common/modal_content', array(
			'modalHeader'	=> "Attendees",
			'modalBody'		=> View::make('pages.admin.appointments.ajax.attendees')
								->withService($service)
								->withAttendees($attendees),
			'modalFooter'	=> false,
		));
	}

	public function removeAttendee()
	{
		if ($this->currentUser->isStaff())
		{
			// Withdraw the user
			Book::withdraw(Input::get('service'), Input::get('user'));

			/*// Get the appointment
			$appointment = $this->appts->find(Input::get('appt'));

			if ($appointment)
			{
				//$user = Input::get('user');

				// Get the user record for this appointment
				$userAppt = $appointment->userAppointments->filter(function($a) use ($user)
				{
					return (int) $a->user_id === (int) $user;
				})->first();

				// Remove the user appointment
				if ($userAppt)
				{
					$userAppt->forceDelete();
				}
			}*/
		}
	}

	public function user($id = false)
	{
		if ($this->currentUser->isStaff())
		{
			if ( ! $id)
			{
				return View::make('pages.admin.appointments.usersAll')
					->withUsers($this->user->all());
			}
			else
			{
				// Get the user
				$user = $this->user->find($id);

				return View::make('pages.admin.appointments.usersAction')
					->withUser($user)
					->withSchedule($this->user->getSchedule($user, false));
			}
		}
		else
		{
			return $this->unauthorized("You do not have permission to manage users' appointments!");
		}
	}

	public function history($id)
	{
		if ($this->currentUser->isStaff())
		{
			// Get the user
			$user = $this->user->find($id);

			return View::make('pages.admin.appointments.usersHistory')
				->withUser($user)
				->withHistory($this->user->getScheduleHistory($user));
		}
		else
		{
			return $this->unauthorized("You do not have permission to manage users' appointments!");
		}
	}

	public function recurring()
	{
		if ($this->currentUser->isStaff())
		{
			$staff = ((bool) $this->currentUser->staff->instruction) 
				? $this->currentUser->staff->id 
				: false;

			return View::make('pages.admin.appointments.recurring')
				->withRecurring($this->appts->getRecurringLessons(false, $staff));
		}
		else
		{
			return $this->unauthorized("You do not have permission to manage recurring appointments!");
		}
	}

	public function editRecurring($id)
	{
		if ($this->currentUser->isStaff())
		{
			// Get the recurring lessons
			$recurring = $this->appts->getRecurringLessons($id);

			// Make sure we only have appointments from today forward
			$starting = $recurring->staffAppointments->filter(function($a)
			{
				return $a->start >= Date::now()->startOfDay();
			});

			foreach ($starting as $s)
			{
				$startingDropdown[$s->start->format(Config::get('bjga.dates.dateFormal'))] = $s->start->format(Config::get('bjga.dates.date'));
			}

			// Sort the dropdown
			ksort($startingDropdown);

			return View::make('pages.admin.appointments.recurringEdit')
				->withRecurring($recurring)
				->withToday(Date::now()->startOfDay())
				->with('startingWith', $startingDropdown);
		}
		
		return $this->unauthorized("You do not have permission to edit recurring appointments!");
	}

	public function updateRecurring($id)
	{
		if ($this->currentUser->isStaff())
		{
			$this->appts->updateRecurringLesson($id, Input::all());

			return Redirect::route('admin.appointment.recurring.edit', array($id))
				->with('message', "Recurring appointment series was successfully updated.")
				->with('messageStatus', 'success');
		}
		else
		{
			return $this->unauthorized("You do not have permission to edit recurring appointments!");
		}
	}

	public function details($id)
	{
		// Get the appointment
		$appointment = $this->appts->find($id);

		return partial('common/modal_content', array(
			'modalHeader'	=> "Appointment Details",
			'modalBody'		=> View::make('pages.admin.appointments.ajax.details')
								->withAppt($appointment),
			'modalFooter'	=> false,
		));
	}

	public function ajaxChangeLocation($firstAppointmentId)
	{
		// Get the appointment
		$appointment = $this->appts->find($firstAppointmentId);

		// Get all the locations
		$locations = $this->locations->listAll('id', 'name');

		return partial('common/modal_content', [
			'modalHeader'	=> "Change Location for This Day",
			'modalBody'		=> View::make('pages.admin.appointments.ajax.change-location')
								->withAppt($appointment)
								->withLocations($locations)
								->withUser($this->currentUser),
			'modalFooter'	=> false,
		]);
	}

	public function changeLocation()
	{
		$message = false;
		$messageStatus = false;

		if ($this->currentUser->isStaff())
		{
			// Get the first appointment
			$firstAppt = $this->appts->find(Input::get('firstAppointment'));

			if ($firstAppt)
			{
				$appointments = $this->currentUser->staff->appointments;

				$apptCollection = $appointments->filter(function($a) use ($firstAppt)
				{
					return $a->start->startOfDay() == $firstAppt->start->startOfDay();
				});

				if ($apptCollection->count() > 0)
				{
					foreach ($apptCollection as $appt)
					{
						if ($appt->service->isLesson())
						{
							$updatedAppt = $appt->fill(['location_id' => Input::get('new_location')]);

							$appt->save();

							// Fire the event
							Event::fire('appointment.location', [
								$updatedAppt,
								$appt->userAppointments->first()
							]);
						}
					}

					$messageStatus = 'success';
					$message = "Appointment location updated.";
				}
			}
		}

		return Redirect::route('home')
			->with('message', $message)
			->with('messageStatus', $messageStatus);
	}

	public function cancelRemainingSeries()
	{
		if ($this->currentUser->access() == 4)
		{
			// Get all appointments in the series
			$series = $this->appts->getRecurringLessons(Input::get('series'));

			// Get the start of today
			$today = Date::now()->startOfDay();

			$series->staffAppointments->each(function($s) use ($today)
			{
				if ($s->start->gte($today))
				{
					// Remove the user appointment
					$s->userAppointments->first()->forceDelete();

					// Now remove the staff appointment
					$s->forceDelete();
				}
			});

			return json_encode([]);

			/*// Filter down to just things moving forward
			$series = $series->filter(function($s) use ($today)
			{
				return $s->start->gte($today);
			});

			foreach ($series as $lesson)
			{
				// Remove the user lesson
				$lesson->userAppointments->first()->forceDelete();

				// Remove the staff lesson
				$lesson->forceDelete();
			}*/
		}
	}

	public function ajaxAssociateGoal($lessonId)
	{
		// Get the lesson
		$lesson = $this->appts->find($lessonId);

		// Get the user
		$user = $lesson->userAppointments->first()->user->load('plan', 'plan.activeGoals');

		// Get all the active goals
		$goals = $user->plan->activeGoals->lists('title', 'id');

		return partial('common/modal_content', [
			'modalHeader'	=> "Associate Lesson with Development Plan Goal",
			'modalBody'		=> View::make('pages.admin.appointments.ajax.associate-goal')
								->withPlan($user->plan)
								->withGoals($goals)
								->withUser($user)
								->withLesson($lesson),
			'modalFooter'	=> false,
		]);
	}

	public function associateGoal()
	{
		// Do the association
		$this->appts->associateLessonWithGoal(Input::all());

		Event::fire('goal.lesson.associate');

		return Redirect::back()
			->with('message', "Lesson has been associated with the development plan goal.")
			->with('messageStatus', 'success');
	}

}