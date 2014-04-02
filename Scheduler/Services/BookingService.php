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

		if (array_key_exists('start', $data))
		{
			$rawStart = str_replace(' AM', '', $data['start']);
			$rawStart = str_replace(' PM', '', $rawStart);

			$start = Date::createFromFormat('Y-m-d H:i', $data['date'].' '.$rawStart);
		}
		else
		{
			$start = Date::createFromFormat('Y-m-d G:i', $data['date'].' '.$data['time']);
		}

		if (array_key_exists('end', $data))
		{
			$rawEnd = str_replace(' AM', '', $data['end']);
			$rawEnd = str_replace(' PM', '', $rawEnd);

			$end = Date::createFromFormat('Y-m-d H:i', $data['date'].' '.$rawEnd);
		}
		else
		{
			$end = $start->copy()->addMinutes($service->duration);
		}

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
		{
			$userApptRecord['paid'] = (int) true;
		}

		// Staff members get free lessons, so we need to take that into account
		if ($user->isStaff())
		{
			$userApptRecord = array_merge($userApptRecord, array('paid' => (int) true, 'amount' => 0));
		}

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

	public function cancel($appointmentId, $reason, $cancelAll = false)
	{
		// Get the staff appointment
		$staffAppt = StaffAppointmentModel::find($appointmentId);

		if ($staffAppt)
		{
			// Get the current user
			$user = Auth::user();

			// Start an array for holding email addresses
			$emails = array();

			if ((int) $user->id === (int) $staffAppt->service->staff->user->id)
			{
				$emails = $this->cancelByInstructor($staffAppt, $user, $cancelAll);
				$type = 'instructor';
			}
			else
			{
				$emails = $this->cancelByStudent($staffAppt, $user, $cancelAll);
				$type = 'student';
			}

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
		 * Non-recurring services
		 */
		if ( ! $service->isRecurring())
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
		}

		/**
		 * Recurring services
		 */
		if ($service->isRecurring())
		{
			if ($cancelAll)
			{
				// Get today
				$now = Date::now();

				if ($service->isLesson())
				{
					// Get the start date for the series
					$seriesStart = $staffAppt->recur->staffAppointments->sortBy(function($s)
					{
						return $s->start;
					})->first()->start;

					// Get the entire collection of staff appointments
					$staffSeries = $staffAppt->recur->staffAppointments()->withTrashed()->get();
				}
				else
				{
					// Get the start date for the series
					$seriesStart = $staffAppt->service->appointments->sortBy(function($s)
					{
						return $s->start;
					})->first()->start;

					// Get the entire collection of staff appointments
					$staffSeries = $staffAppt->service->appointments()->withTrashed()->get();
				}

				foreach ($staffSeries as $seriesItem)
				{
					foreach ($seriesItem->userAppointments()->withTrashed()->get() as $userAppt)
					{
						// Get the student email addresses
						$emails[] = $userAppt->user->email;

						if ($now > $seriesStart)
						{
							// Delete the user appointment
							$userAppt->delete();

							// Delete the staff appointment
							//$seriesItem->delete();
						}
						else
						{
							// Delete the user appointment
							$userAppt->forceDelete();

							// Delete the staff appointment
							//$seriesItem->forceDelete();
						}
					}

					if ($now > $seriesStart)
					{
						// Delete the staff appointment
						$seriesItem->delete();
					}
					else
					{
						// Delete the staff appointment
						$seriesItem->forceDelete();
					}
				}
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
			}
		}

		return array_unique($emails);
	}

	protected function cancelByStudent($staffAppt, $user, $cancelAll)
	{
		// Get the service
		$service = $staffAppt->service;

		// Array of email addresses
		$emails = array();

		/**
		 * Non-recurring services
		 */
		if ( ! $service->isRecurring())
		{
			$userAppointments = ($service->isLesson()) 
				? $staffAppt->userAppointments
				: $staffAppt->userAppointments->filter(function($u) use ($user)
					{
						return (int) $u->user_id === (int) $user->id;
					});

			foreach ($userAppointments as $userAppt)
			{
				// Delete the user appointment
				$userAppt->forceDelete();

				// Delete the staff appointment if it's a lesson
				if ($service->isLesson())
				{
					$staffAppt->delete();
				}
			}
		}

		/**
		 * Recurring services
		 */
		if ($service->isRecurring())
		{
			if ($cancelAll)
			{
				// Get today
				$now = Date::now();

				if ($service->isLesson())
				{
					// Get the start date for the series
					$seriesStart = $staffAppt->recur->staffAppointments->sortBy(function($s)
					{
						return $s->start;
					})->first()->start;

					// Get the entire collection of staff appointments
					$staffSeries = $staffAppt->recur->staffAppointments()->withTrashed()->get();
				}

				if ($service->isProgram())
				{
					// Get the start date for the series
					$seriesStart = $staffAppt->occurrence->staffAppointments->sortBy(function($s)
					{
						return $s->start;
					})->first()->start;

					// Get the entire collection of staff appointments
					$staffSeries = $service->appointments()->withTrashed()->get();
				}

				foreach ($staffSeries as $seriesItem)
				{
					$userAppointments = ($service->isLesson()) 
						? $seriesItem->userAppointments()->withTrashed()->get()
						: $seriesItem->userAppointments()->withTrashed()->get()->filter(function($u) use ($user)
							{
								return (int) $u->user_id === (int) $user->id;
							});

					foreach ($userAppointments as $userAppt)
					{
						if ($now < $seriesStart)
						{
							// Delete the user appointment
							$userAppt->forceDelete();

							// Delete the staff appointment
							if ($service->isLesson())
							{
								$seriesItem->delete();
							}
						}
					}
				}
			}
			else
			{
				$userAppointments = ($service->isLesson()) 
					? $staffAppt->userAppointments
					: $staffAppt->userAppointments->filter(function($u) use ($user)
						{
							return (int) $u->user_id === (int) $user->id;
						});

				foreach ($userAppointments as $userAppt)
				{
					// Delete the user appointment
					$userAppt->delete();

					// Delete the staff appointment
					if ($service->isLesson())
					{
						$staffAppt->delete();
					}
				}
			}
		}

		return array($staffAppt->staff->user->email);
	}

}