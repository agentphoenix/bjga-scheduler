<?php namespace Scheduler\Controllers;

use Book,
	Date,
	View,
	Event,
	Input,
	Redirect,
	ServiceValidator,
	UserAppointmentModel,
	StaffAppointmentModel,
	UserRepositoryInterface,
	ServiceRepositoryInterface,
	StaffAppointmentRepositoryInterface;

class AppointmentController extends BaseController {

	protected $user;
	protected $appts;
	protected $service;

	public function __construct(ServiceRepositoryInterface $service,
			StaffAppointmentRepositoryInterface $appts,
			UserRepositoryInterface $user)
	{
		parent::__construct();

		$this->user = $user;
		$this->appts = $appts;
		$this->service = $service;
	}

	public function index()
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			return View::make('pages.admin.appointments.index');
		}
		else
		{
			$this->unauthorized("You do not have permission to manage appointments!");
		}
	}

	public function create()
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			return View::make('pages.admin.appointments.create')
				->withServices(array('0' => "Please choose one") + $this->service->getValues('lesson', true));
		}
		else
		{
			$this->unauthorized("You do not have permission to create appointments!");
		}
	}

	public function store()
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			// Do the booking
			Book::lesson(Input::all(), true, (bool) Input::get('email_student', 0));

			return Redirect::route('admin.appointment.index')
				->with('message', 'Appointment was successfully created.')
				->with('messageStatus', 'success');
		}
		else
		{
			$this->unauthorized("You do not have permission to create appointments!");
		}
	}

	public function edit($id)
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			return View::make('pages.admin.appointments.edit')
				->withAppointment($this->appts->find($id));
		}
		else
		{
			$this->unauthorized("You do not have permission to edit appointments!");
		}
	}

	public function update($id)
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			// Update the staff appointment
			$sa = StaffAppointmentModel::find(Input::get('staff_appointment_id'));
			$sa->update(Input::get('staff'));

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
			$this->unauthorized("You do not have permission to update appointments!");
		}
	}

	public function attendees($type, $id)
	{
		// Get the service
		$service = $this->service->find($id);

		// Get the attendees
		$attendees = ($type == 'service')
			? $this->service->getAttendees($id)
			: $this->appts->getAttendees($id);

		//sd($attendees);

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
			// Get the appointment
			$appointment = $this->appts->find(Input::get('appt'));

			if ($appointment)
			{
				$user = Input::get('user');

				// Get the user record for this appointment
				$userAppt = $appointment->userAppointments->filter(function($a) use ($user)
				{
					return (int) $a->user_id === (int) $user;
				})->first();

				// Remove the user appointment
				$userAppt->forceDelete();
			}
		}
	}

	public function user($id = false)
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
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
			$this->unauthorized("You do not have permission to manage users' appointments!");
		}
	}

	public function history($id)
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			// Get the user
			$user = $this->user->find($id);

			return View::make('pages.admin.appointments.usersHistory')
				->withUser($user)
				->withHistory($this->user->getScheduleHistory($user));
		}
		else
		{
			$this->unauthorized("You do not have permission to manage users' appointments!");
		}
	}

	public function recurring()
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			return View::make('pages.admin.appointments.recurring')
				->withRecurring($this->appts->getRecurringLessons());
		}
		else
		{
			$this->unauthorized("You do not have permission to manage recurring appointments!");
		}
	}

	public function editRecurring($id)
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			return View::make('pages.admin.appointments.recurringEdit')
				->withRecurring($this->appts->getRecurringLessons($id))
				->withToday(Date::now()->startOfDay());
		}
		else
		{
			$this->unauthorized("You do not have permission to edit recurring appointments!");
		}
	}

	public function updateRecurring($id)
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			$this->appts->updateRecurringLesson($id, Input::all());

			return Redirect::route('admin.appointment.recurring.edit', array($id))
				->with('message', "Recurring appointment series was successfully updated.")
				->with('messageStatus', 'success');;
		}
		else
		{
			$this->unauthorized("You do not have permission to edit recurring appointments!");
		}
	}

}