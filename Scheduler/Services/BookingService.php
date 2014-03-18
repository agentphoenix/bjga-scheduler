<?php namespace Scheduler\Services;

use Auth,
	Date,
	Event,
	UserAppointmentModel,
	StaffAppointmentModel,
	UserRepositoryInterface,
	ServiceRepositoryInterface,
	StaffAppointmentRecurModel;

class BookingService {

	protected $user;
	protected $service;

	public function __construct(ServiceRepositoryInterface $service,
			UserRepositoryInterface $user)
	{
		$this->user = $user;
		$this->service = $service;
	}

	public function block(array $data)
	{
		// Create a new appointment
		$block = StaffAppointmentModel::create(array(
			'staff_id'		=> $data['staff'],
			'service_id'	=> 1,
			'start'			=> $data['start'],
			'end'			=> $data['end'],
		));

		// Get the current user
		$user = Auth::user();

		// Fire the lesson booking event
		Event::fire('book.block.created', array($user, $block));
	}

	public function lesson(array $data, $staffCreated = false, $sendEmail = true)
	{
		// Get the service
		$service = $this->service->find($data['service_id']);

		// Build the start date
		$start = (array_key_exists('start', $data))
			? Date::createFromFormat('Y-m-d H:i A', $data['date'].' '.$data['start'])
			: Date::createFromFormat('Y-m-d G:i', $data['date'].' '.$data['time']);

		// Build the end date
		$end = (array_key_exists('end', $data))
			? Date::createFromFormat('Y-m-d H:i A', $data['date'].' '.$data['end'])
			: $start->copy()->addMinutes($service->duration);

		// Build the price
		$price = (array_key_exists('price', $data)) ? $data['price'] : $service->price;

		// Get the user
		$user = $this->user->find((int) $data['user']);

		// Set the initial appointment record
		$apptRecord = array(
			'staff_id'		=> $service->staff->id,
			'service_id'	=> $service->id,
			'start'			=> $start,
			'end'			=> $end,
		);

		// Set the initial user appointment record
		$userApptRecord = array(
			'user_id'	=> $user->id,
			'amount'	=> $price,
		);

		// Automatically mark free services as paid
		if ($userApptRecord['amount'] == 0)
			$userApptRecord['paid'] = (int) true;

		// Staff members get free lessons, so we need to take that into account
		if ($user->isStaff())
			$userApptRecord = array_merge($userApptRecord, array('paid' => (int) true, 'amount' => 0));

		// If we have multiple occurrences, we need to make sure everything is
		// created properly
		if ($service->occurrences > 1)
		{
			// Create a new recurring record
			$recurItem = StaffAppointmentRecurModel::create($apptRecord);

			// Book the staff member
			$bookApptArr = array_merge($apptRecord, array('recur_id' => $recurItem->id));
			$bookStaff = StaffAppointmentModel::create($bookApptArr);

			// Book the user
			$userApptArr = array_merge(
				$userApptRecord,
				array('appointment_id' => $bookStaff->id, 'recur_id' => $recurItem->id)
			);
			$bookUser = UserAppointmentModel::create($userApptArr);

			// Grab a copy of the start and end times so we can add days
			$newStartDate = $recurItem->start->copy();
			$newEndDate = $recurItem->end->copy();

			// Loop through and create all the appointments
			for ($i = 2; $i <= $service->occurrences; $i++)
			{
				// Create the staff appointments
				$sa = StaffAppointmentModel::create(array(
					'staff_id'		=> $service->staff->id,
					'service_id'	=> $service->id,
					'recur_id'		=> $recurItem->id,
					'start'			=> ($service->occurrences_schedule > 0) 
						? $newStartDate->addDays($service->occurrences_schedule) : null,
					'end'			=> ($service->occurrences_schedule > 0) 
						? $newEndDate->addDays($service->occurrences_schedule) : null,
				));

				// Create the user appointments
				$ua = UserAppointmentModel::create(array(
					'appointment_id'	=> $sa->id,
					'user_id'			=> $user->id,
					'recur_id'			=> $recurItem->id,
					'amount'			=> ($user->isStaff()) ? 0 : $price,
					'paid'				=> ($user->isStaff() or $price == 0) ? (int) true : (int) false,
				));
			}
		}
		else
		{
			// Book the staff appointment
			$bookStaff = StaffAppointmentModel::create($apptRecord);

			// Book the user appointment
			$userApptArr = array_merge($userApptRecord, array('appointment_id' => $bookStaff->id));
			$bookUser = UserAppointmentModel::create($userApptArr);
		}

		// Fire the lesson booking event
		if ($staffCreated)
		{
			Event::fire('appointment.created', array($service, $bookStaff, $bookUser, $sendEmail));
		}
		else
		{
			Event::fire('book.lesson.created', array($service, $bookStaff, $bookUser));
		}
	}

	public function program(array $data)
	{
		// Get the service
		$service = $this->service->find($data['service_id']);

		// Get the user
		$user = $this->user->find((int) $data['user']);

		// Set the initial user appointment record
		$userApptRecord = array(
			'user_id'	=> $user->id,
			'amount'	=> $service->price,
		);

		// Automatically mark free services as paid
		if ($userApptRecord['amount'] == 0)
			$userApptRecord['paid'] = (int) true;

		// Staff members get free lessons, so we need to take that into account
		if ($user->isStaff())
			$userApptRecord = array_merge($userApptRecord, array('paid' => (int) true, 'amount' => 0));

		if ($service->appointments->count() > 1)
		{
			foreach ($service->appointments as $a)
			{
				// Book the user
				$userApptArr = array_merge(
					$userApptRecord,
					array('appointment_id' => $a->id, 'occurrence_id' => $a->occurrence_id)
				);
				$bookUser = UserAppointmentModel::create($userApptArr);
			}
		}
		else
		{
			// Book the user
			$userApptArr = array_merge(
				$userApptRecord,
				array('appointment_id' => $service->appointments()->first()->id)
			);
			$bookUser = UserAppointmentModel::create($userApptArr);
		}

		// Fire the program booking event
		Event::fire('book.program.created', array($service, $bookUser));
	}

	public function withdraw($appointmentId, $reason)
	{
		// Get the user
		$user = Auth::user();

		// Find the staff appointment
		$staffAppt = StaffAppointmentModel::find($appointmentId);

		if ($staffAppt)
		{
			// Grab the service
			$service = $staffAppt->service;

			if ($staffAppt->userAppointments->count() > 0)
			{
				// Get the user's appointment record
				$userAppt = $staffAppt->userAppointments->filter(function($s) use ($user)
				{
					return (int) $s->user_id === (int) $user->id;
				})->first();

				// Remove the user appointment
				if ($service->isRecurring())
					$userAppt->delete();
				else
					$userAppt->forceDelete();

				// Remove the staff appointment if it's a lesson
				if ($service->isLesson())
				{
					if ($service->isRecurring())
						$staffAppt->delete();
					else
						$staffAppt->forceDelete();
				}

				Event::fire('book.cancel.student', array($staffAppt, $user, $reason));
			}
		}
	}

	/**
	 * @param	string	$type			Who is taking the action (student, instructor)
	 * @param	int		$appointmentId	Staff appointment ID
	 * @param	string	$reason			The reason the appointment is being cancelled
	 */
	public function old_cancel($type, $appointmentId, $reason)
	{
		// Find the staff appointment
		$staffAppt = StaffAppointmentModel::find($appointmentId);

		// Get the current user
		$user = Auth::user();

		if ($staffAppt)
		{
			// Get the service
			$service = $staffAppt->service;

			// Start an array for holding email addresses
			$emails = array();

			/**
			 * Recurring lesson.
			 */
			if ($service->isLesson() and $service->isRecurring() and $type == 'instructor')
			{
				//
			}

			/**
			 * Non-recurring lesson.
			 */
			if ($service->isLesson() and ! $service->isRecurring())
			{
				// Get the user appointment
				$userAppt = $staffAppt->userAppointments->first();

				// Send the email to...
				$emails[] = $userAppt->user->email;

				// Delete the user appointment record
				$userAppt->forceDelete();

				// Delete the staff appointment
				$staffAppt->forceDelete();
			}

			/**
			 * Program.
			 */
			if ($service->isProgram())
			{
				foreach ($staffAppt->userAppointments as $ua)
				{
					// Get the email addresses
					$emails[] = $ua->user->email;

					// Delete the user appointment
					if ($service->isRecurring())
					{
						$ua->delete();
					}
					else
					{
						$ua->forceDelete();
					}
				}

				// Delete the staff appointment
				$staffAppt->delete();
			}

			if ($staffAppt->userAppointments->count() > 0)
			{
				$emails = array();

				foreach ($staffAppt->userAppointments as $ua)
				{
					// Get the email address
					$emails[] = $ua->user->email;

					// Delete the appointment
					if ($service->isRecurring())
						$ua->delete();
					else
						$ua->forceDelete();
				}

				// Only cancel the staff appointment if it's a lesson
				if ($staffAppt->service->isLesson())
				{
					if ($service->isRecurring())
						$staffAppt->delete();
					else
						$staffAppt->forceDelete();
				}
			}

			Event::fire('book.cancel.instructor', array($staffAppt, $emails, $reason));
		}
	}

	public function cancel($type, $appointmentId, $reason, $cancelAll = false)
	{
		// Get the staff appointment
		$staffAppt = StaffAppointmentModel::find($appointmentId);

		if ($staffAppt)
		{
			// Get the current user
			$user = Auth::user();

			// Start an array for holding email addresses
			$emails = array();

			if ($type == 'instructor')
			{
				$emails = $this->cancelByInstructor($staffAppt, $user, $cancelAll);
			}

			if ($type == 'student')
			{
				$emails = $this->cancelByStudent($staffAppt, $user, $cancelAll);
			}

			\Log::info("Emails: ".implode(',', $emails));

			// Fire the event
			Event::fire("book.cancel.{$type}", array($staffAppt, $user, $emails, $reason));
		}
	}

	protected function cancelByInstructor($staffAppt, $user, $cancelAll)
	{
		// Get the service
		$service = $staffAppt->service;

		// Array of email addresses
		$emails = array();

		/**
		 * Non-recurring lesson.
		 */
		if ($service->isLesson() and ! $service->isRecurring())
		{
			// Get the user appointment
			$userAppt = $staffAppt->userAppointments->first();

			// Get the student's email address
			$emails[] = $userAppt->user->email;

			// Delete the user appointment record
			$userAppt->forceDelete();

			// Delete the staff appointment
			$staffAppt->forceDelete();

			\Log::info('instructor.lesson.nonrecurring');
		}

		/**
		 * Recurring lesson.
		 */
		if ($service->isLesson() and $service->isRecurring())
		{
			if ($cancelAll)
			{
				// Get today
				$now = Date::now();

				// Get the start date for the series
				$seriesStart = $staffAppt->recur->staffAppointments->sortBy(function($s)
				{
					return $s->start;
				})->first()->start;

				// Get the entire collection of staff appointments
				$staffSeries = $staffAppt->recur->staffAppointments;

				foreach ($staffSeries as $seriesItem)
				{
					// Get the user appointment
					$userAppt = $seriesItem->userAppointments->first();

					// Get the student email address
					$emails[] = $userAppt->user->email;

					if ($now > $seriesStart)
					{
						// Delete the user appointment
						$userAppt->delete();

						// Delete the staff appointment
						$seriesItem->delete();
					}
					else
					{
						// Delete the user appointment
						$userAppt->forceDelete();

						// Delete the staff appointment
						$seriesItem->forceDelete();
					}

					\Log::info('instructor.lesson.recurring.all');
				}
			}
			else
			{
				// Get the user appointment
				$userAppt = $staffAppt->userAppointments->first();

				// Get the student's email address
				$emails[] = $userAppt->user->email;

				// Delete the user appointment
				$userAppt->delete();

				// Delete the staff appointment
				$staffAppt->delete();

				\Log::info('instructor.lesson.recurring.instance');
			}
		}

		/**
		 * Non-recurring program.
		 */
		if ($service->isProgram() and ! $service->isRecurring())
		{
			foreach ($staffAppt->userAppointments as $userAppt)
			{
				// Get the attendee email addresses
				$emails[] = $userAppt->user->email;

				// Delete the user appointment
				$userAppt->forceDelete();

				// Delete the staff appointment
				$staffAppt->delete();
			}

			\Log::info('instructor.program.nonrecurring');
		}

		/**
		 * Recurring program.
		 */
		if ($service->isProgram() and $service->isRecurring())
		{
			if ($cancelAll)
			{
				foreach ($service->appointments as $sa)
				{
					foreach ($sa->userAppointments as $userAppt)
					{
						// Get the student email addresses
						$emails[] = $userAppt->user->email;

						// Delete the user appointment
						$userAppt->delete();
					}
					
					// Delete the staff appointments
					$sa->delete();
				}

				\Log::info('instructor.program.recurring.all');
			}
			else
			{
				foreach ($staffAppt->userAppointments as $userAppt)
				{
					// Get the student's email addresses
					$emails[] = $userAppt->user->email;

					// Delete the user appointment
					$userAppt->delete();

					// Delete the staff appointment
					$staffAppt->delete();
				}

				\Log::info('instructor.program.recurring.instance');
			}
		}

		return array_unique($emails);
	}

	protected function cancelByStudent($staffAppt, $user, $cancelAll)
	{
		// Get the service
		$service = $staffAppt->service;

		/**
		 * Recurring lesson.
		 */
		if ($service->isLesson() and $service->isRecurring())
		{
			if ($cancelAll)
			{
				// Get today
				$now = Date::now();

				// Get the start date for the series
				$seriesStart = $staffAppt->recur->staffAppointments->sortBy(function($s)
				{
					return $s->start;
				})->first()->start;

				// Get the entire collection of user appointments
				$userSeries = $staffAppt->recur->userAppointments->filter(function($u) use ($user)
				{
					return (int) $u->user_id === (int) $user->id;
				});

				foreach ($userSeries as $userAppt)
				{
					// Get the instructor email address
					$instructorEmail = $staffAppt->staff->user->email;

					// The series has already started
					if ($now > $seriesStart)
					{
						// Delete the user appointment
						$userAppt->delete();

						// Delete the staff appointment
						$userAppt->appointment->delete();
					}
					else
					{
						// Delete the user appointment
						$userAppt->forceDelete();

						// Delete the staff appointment
						$userAppt->appointment->forceDelete();
					}
				}

				\Log::info('student.lesson.recurring.all');
			}
			else
			{
				// Get the user appointment
				$userAppt = $staffAppt->userAppointments->first();

				// Get the instructor's email address
				$instructorEmail = $staffAppt->staff->user->email;

				// Delete the user appointment
				$userAppt->delete();

				// Delete the staff appointment
				$staffAppt->delete();

				\Log::info('student.lesson.recurring.instance');
			}
		}

		/**
		 * Non-recurring lesson.
		 */
		if ($service->isLesson() and ! $service->isRecurring())
		{
			// Get the user appointment
			$userAppt = $staffAppt->userAppointments->first();

			// Get the student's email address
			$instructorEmail = $staffAppt->staff->user->email;

			// Delete the user appointment record
			$userAppt->forceDelete();

			// Delete the staff appointment
			$staffAppt->forceDelete();

			\Log::info('student.lesson.nonrecurring');
		}

		/**
		 * Recurring program.
		 */
		if ($service->isProgram() and $service->isRecurring())
		{
			if ($cancelAll)
			{
				// Get today
				$now = Date::now();

				// Get the start date for the series
				$seriesStart = $staffAppt->recur->staffAppointments->sortBy(function($s)
				{
					return $s->start;
				})->first()->start;

				// Get the entire collection of user appointments
				$userSeries = $staffAppt->recur->userAppointments;

				foreach ($userSeries as $userAppt)
				{
					if ((int) $userAppt->user->id === (int) $user->id)
					{
						// Delete the user appointment
						$userAppt->delete();
					}
				}

				// Get the instructor email address
				$instructorEmail = $staffAppt->staff->user->email;

				\Log::info('student.program.recurring.all');
			}
			else
			{
				// Get the user's appointment record
				$userAppt = $staffAppt->userAppointments->filter(function($u) use ($user)
				{
					return (int) $u->user_id === (int) $user->id;
				})->first();

				// Delete the user appointment
				$userAppt->delete();

				// Get the instructor's email address
				$instructorEmail = $staffAppt->staff->user->email;

				\Log::info('student.program.recurring.instance');
			}
		}

		/**
		 * Non-recurring program.
		 */
		if ($service->isProgram() and ! $service->isRecurring())
		{
			// Get this user's appointment record
			$userAppt = $staffAppt->userAppointments->filter(function($u) use ($user)
			{
				return (int) $u->user_id === (int) $user->id;
			})->first();

			// Delete the user appointment
			$userAppt->forceDelete();

			// Get the instructor email address
			$instructorEmail = $staffAppt->staff->user->email;

			\Log::info('student.program.nonrecurring');
		}

		return array($instructorEmail);
	}

}