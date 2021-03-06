<?php namespace Scheduler\Controllers;

use Auth,
	Book,
	Date,
	Mail,
	View,
	Input,
	Config,
	Session,
	Redirect,
	UserAppointmentModel,
	ServiceOccurrenceModel,
	UserRepositoryInterface,
	StaffRepositoryInterface,
	ServiceRepositoryInterface,
	LocationRepositoryInterface,
	StaffScheduleRepositoryInterface,
	StaffAppointmentRepositoryInterface;

class AjaxController extends BaseController {

	protected $schedule;
	protected $service;
	protected $user;
	protected $appointment;
	protected $staff;
	protected $icons;
	protected $location;

	public function __construct(StaffScheduleRepositoryInterface $schedule,
			ServiceRepositoryInterface $service,
			UserRepositoryInterface $user,
			StaffAppointmentRepositoryInterface $appointment,
			StaffRepositoryInterface $staff,
			LocationRepositoryInterface $location)
	{
		parent::__construct();

		$this->schedule = $schedule;
		$this->service = $service;
		$this->user = $user;
		$this->appointment = $appointment;
		$this->staff = $staff;
		$this->location = $location;

		$this->icons = Config::get('icons');
	}

	public function changePassword($id)
	{
		if ($this->currentUser->id == $id)
		{
			// Get the user
			$user = $this->user->find($id);
			
			return partial('common/modal_content', array(
				'modalHeader'	=> "Change Password",
				'modalBody'		=> View::make('pages.ajax.changePassword')->with('user', $user),
				'modalFooter'	=> false,
			));
		}
	}

	public function deleteService($id)
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			$service = $this->service->find($id);

			if ($service)
			{
				return partial('common/modal_content', array(
					'modalHeader'	=> "Delete Service",
					'modalBody'		=> View::make('pages.ajax.deleteService')->with('service', $service),
					'modalFooter'	=> false,
				));
			}
		}
	}

	public function deleteStaff($id)
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			$staff = $this->staff->find($id);

			if ($staff)
			{
				return partial('common/modal_content', array(
					'modalHeader'	=> "Remove Staff Member",
					'modalBody'		=> View::make('pages.ajax.deleteStaff')->with('staff', $staff),
					'modalFooter'	=> false,
				));
			}
		}
	}

	public function deleteUser($id)
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			$user = $this->user->find($id);

			if ($user)
			{
				return partial('common/modal_content', array(
					'modalHeader'	=> "Delete User",
					'modalBody'		=> View::make('pages.ajax.deleteUser')->with('user', $user),
					'modalFooter'	=> false,
				));
			}
		}
	}

	public function getAvailability()
	{
		// Get the date
		$date = Date::createFromFormat("Y-m-d", Input::get('date'));

		// Get today
		$today = Date::now();

		// Get the service
		$service = $this->service->find(Input::get('service'));

		// Get the availability
		$availability = $this->schedule->getAvailability($service->staff_id, $date, $service);

		// Now find the available times
		$availableTime = $this->schedule->findTimeBlock($availability, $service);

		// If they're looking for something today, only show what's available
		// in the future and not any available slots in the past
		if ($date->startOfDay() == $today->copy()->startOfDay())
		{
			foreach ($availableTime as $key => $time)
			{
				if ($today > $time)
				{
					unset($availableTime[$key]);
				}
			}
		}

		return View::make('pages.ajax.availability')
			->withAvailability($availableTime)
			->withDate($date);
	}

	public function getProgramDetails()
	{
		$serviceId = Input::get('service');

		$service = $this->service->find($serviceId);

		if ($service)
		{
			return View::make('pages.ajax.programServiceDetails')
				->withDates($service->serviceOccurrences->sortBy(function($s) { return $s->start; }))
				->withPrice($service->present()->price)
				->withLocation($service->present()->location);
		}

		return View::make('partials.common.alert')
			->withClass('alert-warning')
			->withContent("No service found!");
	}

	public function getLessonDetails()
	{
		$serviceId = Input::get('service');

		$service = $this->service->find($serviceId);

		if ($service)
		{
			// Build a date object
			$date = Date::createFromFormat('Y-m-d', Input::get('date'));

			// Get the location from the instructor
			$location = $service->staff->schedule->filter(function($s) use ($date)
			{
				return (int) $s->day === (int) $date->dayOfWeek;
			})->first()->location;

			return View::make('pages.ajax.lessonServiceDetails')
				->withService($service)
				->withLocation($location->present()->name);
		}

		return View::make('partials.common.alert')
			->withClass('alert-warning')
			->withContent("No service found!");
	}

	public function getService()
	{
		$service = $this->service->find(Input::get('service'));

		if ($service)
		{
			$appt = $service->appointments->last();

			$output = array();
			$output['service'] = array(
				'id'			=> (int) $service->id,
				'name'			=> (string) $service->name,
				'description'	=> (string) $service->present()->description,
				'price'			=> (string) $service->present()->price,
				'user_limit'	=> (int) $service->user_limit,
			);
			$output['enrolled'] = (int) $service->attendees()->count();

			if ($appt)
			{
				$output['appointment'] = array(
					'id'	=> (int) $appt->id,
					'date'	=> (string) $appt->start->format(Config::get('bjga.dates.date')),
					'start'	=> (string) $appt->start->format(Config::get('bjga.dates.time')),
				);
			}

			return json_encode($output);
		}

		return json_encode(array());
	}

	public function getServiceNew()
	{
		$service = $this->service->find(Input::get('service'));

		if ($service)
		{
			$appt = $service->appointments->last();

			$output = array();
			$output['service'] = array(
				'id'			=> (int) $service->id,
				'name'			=> (string) $service->name,
				'description'	=> (string) $service->present()->description,
				'price'			=> (string) $service->price,
				'user_limit'	=> (int) $service->user_limit,
			);
			$output['enrolled'] = (int) $service->attendees()->count();

			if ($appt)
			{
				$output['appointment'] = array(
					'id'	=> (int) $appt->id,
					'date'	=> (string) $appt->start->format(Config::get('bjga.dates.date')),
					'start'	=> (string) $appt->start->format(Config::get('bjga.dates.time')),
				);
			}

			return json_encode($output);
		}

		return json_encode(array());
	}

	public function postEnroll()
	{
		// Get the appointment ID
		$id = (is_numeric(Input::get('appointment'))) ? Input::get('appointment') : false;

		// Get the appointment object
		$appointment = $this->appointment->find($id);

		$appointment->enroll();

		Session::flash('message', "Appointment has been successfully added.");
		Session::flash('messageStatus', "success");
	}

	public function postNewService()
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			// Get the selected type
			$type = Input::get('type');

			// Get all the services
			$services[] = 'Please choose a service';

			$allServices = $this->service->all();

			if ($allServices->count() > 0)
			{
				foreach ($allServices as $a)
				{
					$services[$a->category->name][$a->id] = $a->name;
				}
			}

			if ($type == 'OneToOne')
			{
				return View::make('pages.ajax.createServiceOneToOne')
					->with('services', $services)
					->with('_icons', $this->icons);
			}

			if ($type == 'OneToMany')
			{
				return View::make('pages.ajax.createServiceOneToMany')
					->with('services', $services)
					->with('_icons', $this->icons);
			}

			if ($type == 'ManyToMany')
			{
				return View::make('pages.ajax.createServiceManyToMany')
					->with('services', $services)
					->with('_icons', $this->icons);
			}
		}
	}

	public function postRemoveServiceScheduleItem()
	{
		// Get the service occurrence ID
		$id = (is_numeric(Input::get('id'))) ? Input::get('id') : false;

		// Get the occurrence
		$occurrence = ServiceOccurrenceModel::find($id);

		if ($occurrence)
		{
			// Remove any user appointments
			if ($occurrence->userAppointments->count() > 0)
			{
				foreach ($occurrence->userAppointments as $a)
				{
					$a->delete();
				}
			}

			// Remove any staff appointments
			if ($occurrence->staffAppointments->count() > 0)
			{
				foreach ($occurrence->staffAppointments as $a)
				{
					$a->delete();
				}
			}

			// Remove the occurrence
			$occurrence->delete();

			return json_encode(array('code' => 1));
		}

		return json_encode(array('code' => 0));
	}

	public function postWithdraw()
	{
		// Withdraw from the service
		Book::withdraw(Input::get('service'), Auth::user()->id);

		Session::flash('message', "You've successfully withdrawn from the program.");
		Session::flash('messageStatus', "success");
	}

	public function updateServiceOrder()
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			// Get the input
			$services = Input::get('service');

			foreach ($services as $key => $value)
			{
				// Update the service
				$this->service->update($value, array('order' => $key + 1));
			}
		}
	}

	public function markAsPaid()
	{
		if ($this->currentUser->isStaff())
		{
			// Get the appointment
			$appointment = UserAppointmentModel::find(Input::get('appt'));

			if ($appointment)
			{
				$appointment->update(array(
					'received'	=> (int) $appointment->amount,
					'paid'		=> (int) true,
				));

				return json_encode(array('code' => 1));
			}

			return json_encode(array('code' => 0));
		}
	}

	public function sendEmailFromService($serviceId, $apptId)
	{
		if ($this->currentUser->isStaff())
		{
			// Get the service
			$service = $this->service->find($serviceId);

			// Get the appointment
			$appointment = $this->appointment->find($apptId);

			if ($service->isLesson())
			{
				$header = 'Email User';
				$recipient = $appointment->userAppointments->first()->user->email;
			}
			else
			{
				$header = 'Email Attendees';

				// Make sure we have an empty array to avoid errors
				$recipientArr = [];

				foreach ($appointment->userAppointments as $a)
				{
					$recipientArr[] = $a->user->email;
				}

				$recipient = implode(',', $recipientArr);
			}

			return partial('common/modal_content', array(
				'modalHeader'	=> $header,
				'modalBody'		=> View::make('pages.ajax.sendEmailService')
									->withService($service)
									->withAppointment($appointment)
									->withRecipients($recipient)
									->withRedirect('home'),
				'modalFooter'	=> false,
			));
		}
	}

	public function sendEmailFromUser($userId)
	{
		if ($this->currentUser->isStaff())
		{
			// Get the user
			$user = $this->user->find($userId);

			if ($user)
			{
				return partial('common/modal_content', array(
					'modalHeader'	=> "Email User",
					'modalBody'		=> View::make('pages.ajax.sendEmailUser')
										->withUser($user)
										->withRedirect('admin.user.index'),
					'modalFooter'	=> false,
				));
			}
		}
	}

	public function sendEmailFromUnpaid($userId)
	{
		if ($this->currentUser->isStaff())
		{
			// Get the user
			$user = $this->user->find($userId);

			if ($user)
			{
				return partial('common/modal_content', array(
					'modalHeader'	=> "Email User",
					'modalBody'		=> View::make('pages.ajax.sendEmailUnpaid')
										->withUser($user)
										->withRedirect('admin.reports.unpaid'),
					'modalFooter'	=> false,
				));
			}
		}
	}

	public function sendEmailToInstructor($apptId)
	{
		// Get the appointment
		$appointment = $this->appointment->find($apptId);

		if ($appointment)
		{
			return partial('common/modal_content', array(
				'modalHeader'	=> "Email Instructor",
				'modalBody'		=> View::make('pages.ajax.sendEmailInstructor')
									->withStaff($appointment->staff)
									->withRedirect('home'),
				'modalFooter'	=> false,
			));
		}
	}

	public function sendEmail()
	{
		$emailData = array(
			'subject'	=> Input::get('subject'),
			'body'		=> Input::get('message'),
			'from'		=> $this->currentUser->name,
		);

		// Get the input
		$input = Input::all();

		// Get the current user
		$user = $this->currentUser;

		Mail::send('emails.sendToUser', $emailData, function($msg) use ($input, $user)
		{
			$recipientArr = explode(',', $input['recipients']);

			$msg->subject(Config::get('bjga.email.subject')." {$input['subject']}")
				->to($recipientArr)
				->from($user->email, $user->name);
		});

		return Redirect::route(Input::get('redirect'))
			->with('message', "Your email has been sent.")
			->with('messageStatus', 'success');
	}

	public function cancelModal($type, $id)
	{
		$view = ($type == 'staff') ? 'pages.ajax.cancelInstructor' : 'pages.ajax.cancelStudent';

		return partial('common/modal_content', array(
			'modalHeader'	=> "Cancel Appointment",
			'modalBody'		=> View::make($view)->withAppointment($this->appointment->find($id)),
			'modalFooter'	=> false,
		));
	}

}