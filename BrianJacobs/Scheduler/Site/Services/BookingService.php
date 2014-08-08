<?php namespace Scheduler\Services;

use App,
	Auth,
	Date,
	Event,
	UserModel,
	CreditModel,
	BookingMetaModel,
	UserAppointmentModel,
	StaffAppointmentModel,
	UserRepositoryInterface,
	ServiceRepositoryInterface,
	StaffAppointmentRecurModel,
	StaffAppointmentRepositoryInterface;
use Illuminate\Support\Collection;

class BookingService {

	protected $userRepo;
	protected $serviceRepo;
	protected $staffApptRepo;

	public function __construct(ServiceRepositoryInterface $service,
			UserRepositoryInterface $user,
			StaffAppointmentRepositoryInterface $staffAppt)
	{
		$this->userRepo = $user;
		$this->serviceRepo = $service;
		$this->staffApptRepo = $staffAppt;
	}

	public function block(array $data)
	{
		// Get the current user
		$user = Auth::user();

		// Create a new appointment
		$block = $this->buildStaffAppointment('block', [
			'staff_id'		=> $data['staff'],
			'service_id'	=> 1,
			'start'			=> $data['start'],
			'end'			=> $data['end'],
			'notes'			=> $data['notes'],
		]);

		// Fire the lesson booking event
		Event::fire('book.block.created', [$user, $block]);
	}

	public function lesson(array $data, $staffCreated = false, $sendEmail = true)
	{
		// Get the user
		$user = $this->userRepo->find($data['user']);

		// Get the service
		$service = $this->serviceRepo->find($data['service_id']);

		// Start a collection for user appointments
		$userApptCollection = new Collection;

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

		// Set the initial appointment record
		$apptRecord = array(
			'staff_id'		=> $service->staff->id,
			'service_id'	=> $service->id,
			'start'			=> $start,
			'end'			=> $end,
			'notes'			=> $data['notes'],
		);

		// Set the initial user appointment record
		$userApptRecord = array(
			'user_id'	=> $user->id,
			'amount'	=> $price,
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

		$bookStaffIds = [];
		$bookUserIds = [];

		// If we have multiple occurrences, we need to make sure everything is created properly
		if ($service->isRecurring())
		{
			// Create a new recurring record
			$recurItem = StaffAppointmentRecurModel::create($apptRecord);

			// Book the staff member
			$bookApptArr = array_merge($apptRecord, array('recur_id' => $recurItem->id));
			$bookStaff = StaffAppointmentModel::create($bookApptArr);
			$bookStaffIds[] = $bookStaff->id;

			// Book the user
			$userApptArr = array_merge(
				$userApptRecord,
				array('appointment_id' => $bookStaff->id, 'recur_id' => $recurItem->id)
			);
			$bookUser = UserAppointmentModel::create($userApptArr);
			$bookUserIds[] = $bookUser->id;

			// Add to the collection of user appointments
			$userApptCollection->push($bookUser);

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
				$bookStaffIds[] = $sa->id;

				// Create the user appointments
				$ua = UserAppointmentModel::create(array(
					'appointment_id'	=> $sa->id,
					'user_id'			=> $user->id,
					'recur_id'			=> $recurItem->id,
					'amount'			=> ($user->isStaff()) ? 0 : $price,
					'paid'				=> ($user->isStaff() or $price == 0) ? (int) true : (int) false,
				));
				$bookUserIds[] = $ua->id;

				// Add to the collection of user appointments
				$userApptCollection->push($ua);
			}
		}
		else
		{
			// Book the staff appointment
			$bookStaff = StaffAppointmentModel::create($apptRecord);
			$bookStaffIds[] = $bookStaff->id;

			// Book the user appointment
			$userApptArr = array_merge($userApptRecord, array('appointment_id' => $bookStaff->id));
			$bookUser = UserAppointmentModel::create($userApptArr);
			$bookUserIds[] = $bookUser->id;

			// Add to the collection of user appointments
			$userApptCollection->push($bookUser);
		}

		// Apply any credits
		$this->applyCredit($user, $userApptCollection);

		// Get the browser object
		$browser = App::make('scheduler.browser');

		// Create the meta record
		BookingMetaModel::create(array(
			'user_id'				=> $user->id,
			'user_name'				=> $user->name,
			'staff_appointment_ids'	=> implode(',', $bookStaffIds),
			'user_appointment_ids'	=> implode(',', $bookUserIds),
			'os'					=> $browser->getPlatform(),
			'browser'				=> $browser->getBrowser().' '.$browser->getVersion(),
		));

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
		// Get the user
		$user = $this->userRepo->find($data['user']);

		// Get the service
		$service = $this->serviceRepo->find($data['service_id']);

		// Start a collection for user appointments
		$userApptCollection = new Collection;

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

				// Add to the collection of user appointments
				$userApptCollection->push($bookUser);
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

			// Add to the collection of user appointments
			$userApptCollection->push($bookUser);
		}

		// Apply any credits
		$this->applyCredit($user, $userApptCollection);

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
			$emails = [];

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
		$emails = [];

		/**
		 * Non-recurring services
		 */
		if ( ! $service->isRecurring())
		{
			foreach ($staffAppt->userAppointments as $userAppt)
			{
				// Get the attendee email addresses
				$emails[] = $userAppt->user->email;

				if ($service->isLesson() and ! $staffAppt->hasStarted() and $userAppt->isPaid())
				{
					// Give the user credit
					CreditModel::create([
						'code'		=> \Str::creditCode(12),
						'type'		=> 'time',
						'value'		=> $service->duration / 60,
						'user_id'	=> $userAppt->user->id,
						'expires'	=> Date::now()->addDay()->addYear()->startOfDay(),
					]);

					// Delete the user appointment
					$userAppt->delete();
				}
				elseif ($service->isLesson() and ! $staffAppt->hasStarted() and ! $userAppt->isPaid() and ($userAppt->received > 0 and $userAppt->received < $service->price))
				{
					// Price per minute
					$pricePerMin = $service->price / $service->duration;

					// How many minutes to credit
					$minutesToCredit = round($userAppt->received / $pricePerMin);

					// Give the user credit
					CreditModel::create([
						'code'		=> \Str::creditCode(12),
						'type'		=> 'time',
						'value'		=> $minutesToCredit / 60,
						'user_id'	=> $userAppt->user->id,
						'expires'	=> Date::now()->addDay()->addYear()->startOfDay(),
					]);

					// Delete the user appointment
					$userAppt->delete();
				}
				else
				{
					// Delete the user appointment
					$userAppt->forceDelete();
				}

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
					
					// Filter the series to make sure we're only getting what we want
					$staffSeries = $staffSeries->filter(function($s) use ($staffAppt)
					{
						return $s->start >= $staffAppt->start;
					});
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

						if ($service->isLesson())
						{
							if ($userAppt->isPaid())
							{
								// Give the credit
								CreditModel::create([
									'code'		=> \Str::creditCode(12),
									'type'		=> 'time',
									'value'		=> $service->duration / 60,
									'user_id'	=> $userAppt->user->id,
									'expires'	=> Date::now()->addDay()->addYear()->startOfDay(),
								]);

								// Delete the user appointment
								$userAppt->delete();
							}
							else
							{
								// Delete the user appointment
								$userAppt->forceDelete();
							}
						}
						else
						{
							// Delete the user appointment
							$userAppt->forceDelete();
						}
					}

					// Delete the staff appointment
					$seriesItem->forceDelete();
				}
			}
			else
			{
				foreach ($staffAppt->userAppointments as $userAppt)
				{
					// Get the student's email addresses
					$emails[] = $userAppt->user->email;

					if ($service->isLesson() and $userAppt->isPaid())
					{
						// Give the user credit
						CreditModel::create([
							'code'		=> \Str::creditCode(12),
							'type'		=> 'time',
							'value'		=> $service->duration / 60,
							'user_id'	=> $userAppt->user->id,
							'expires'	=> Date::now()->addDay()->addYear()->startOfDay(),
						]);

						// Delete the user appointment
						$userAppt->delete();
					}
					else
					{
						// Delete the user appointment
						$userAppt->forceDelete();
					}

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
		$emails = [];

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

	protected function applyUserCredit($user, $price, $duration)
	{
		// Get the user's credits
		$credits = $user->getCredits();

		// Set the new price
		$newPrice = $price;

		$retval = [
			'type'	=> '',
			'value'	=> '',
		];

		if ($credits['money'] > 0)
		{
			// Make sure we're monetary credits
			$money = $user->credits->filter(function($c)
			{
				return $c->type == 'money';
			});

			foreach ($money as $m)
			{
				if ($newPrice > 0)
				{
					// Get the remaining amount
					$remaining = $m->value - $m->claimed;

					if ($remaining >= $newPrice)
					{
						// Update the credits
						$m->update(['claimed' => $m->claimed + $newPrice]);

						// Set the new price
						$newPrice = 0;

						if ($remaining == $newPrice)
						{
							$m->delete();
						}
					}
					else
					{
						// Set the new price
						$newPrice = $newPrice - $remaining;

						// Update the credits
						$m->update(['claimed' => $m->value]);

						// Now delete the credit
						$m->delete();
					}
				}
			}

			$retval['type'] = 'money';
			$retval['value'] = (int) $newPrice;

			return $retval;
		}

		if ($credits['time'] > 0)
		{
			// Make sure we're dealing with time credits
			$time = $user->credits->filter(function($c)
			{
				return $c->type == 'time';
			})->sortBy('expires');

			foreach ($time as $t)
			{
				if ($newPrice > 0)
				{
					// Get the remaining credit in minutes
					$remaining = $t->value - $t->claimed;

					if ((int) $remaining >= (int) $duration)
					{
						// Update the credits
						$t->update(['claimed' => $t->claimed + $duration]);

						// Set the new price
						$newPrice = 0;

						if ((int) $remaining == (int) $duration)
						{
							$t->delete();
						}
					}
					else
					{
						// Get the remaining time
						$remainingTime = $duration - $remaining;

						// Figure out the cost per minute
						$costPerMinute = $newPrice / $duration;

						// Set the new price
						$newPrice = $remainingTime * $costPerMinute;

						// Update the credits
						$t->update(['claimed' => $t->value]);

						// Now delete the credit
						$t->delete();
					}
				}
			}

			$retval['type'] = 'time';
			$retval['value'] = (int) $newPrice;

			return $retval;
		}

		return (int) $newPrice;
	}

	protected function buildUserAppointment(array $data)
	{
		# code...
	}

	protected function buildStaffAppointment($type, array $data)
	{
		return $this->staffApptRepo->create($data);
	}

	protected function applyCredit(UserModel $user, Collection $items)
	{
		// Get the credits for the user
		$credits = $user->credits;

		if ($items->count() > 0)
		{
			// Sort the collection
			$items = $items->sortBy('id');

			foreach ($items as $item)
			{
				// Apply any time credit
				$this->applyTimeCredits($item, $credits);

				// Apply any monetary credit
				$this->applyMoneyCredits($item, $credits);
			}
		}
	}

	protected function applyTimeCredits(&$item, $credits)
	{
		if ($item->due() > 0)
		{
			// Filter the credits
			$credits = $credits->filter(function($c)
			{
				return $c->type == 'time';
			})->sortBy('expires');

			// Get the service
			$service = $item->appointment->service;

			if ($credits->count() > 0)
			{
				foreach ($credits as $credit)
				{
					// Get the remaining time on the credit
					$remaining = $credit->value - $credit->claimed;

					if ($remaining >= $service->duration)
					{
						// Update the credit
						$credit->update(['claimed' => $service->duration / 60]);

						// If we've used the credit up, remove it
						if ($credit->value == $credit->claimed)
						{
							$credit->delete();
						}

						// Update the item
						$item->update([
							'paid'		=> (int) true,
							'amount'	=> 0,
							'received'	=> 0,
						]);
					}
					else
					{
						// Get the remaining time
						$remainingTime = $service->duration - $remaining;

						// Figure out the cost per minute
						$costPerMinute = $service->price / $service->duration;

						// Update the item
						$item->update([
							'amount'	=> $remainingTime * $costPerMinute,
							'received'	=> 0,
						]);

						// Update the credit
						$credit->update(['claimed' => $credit->value / 60]);

						// Remove the credit
						$credit->delete();
					}
				}

				// Get the staff appointment
				$appt = $item->appointment;

				// Add notes to the staff appointment
				$appt->update(['notes' => $appt->notes."\r\n(Code: ".$credit->code.")"]);
			}
		}
	}

	protected function applyMoneyCredits(&$item, $credits)
	{
		if ($item->due() > 0)
		{
			// Filter the credits
			$credits = $credits->filter(function($c)
			{
				return $c->type == 'money';
			});

			if ($credits->count() > 0)
			{
				foreach ($credits as $credit)
				{
					// Get the remaining credit available
					$remaining = $credit->value - $credit->claimed;

					if ($remaining >= $item->due())
					{
						// Update the credit
						$credit->update(['claimed' => $credit->claimed + $item->due()]);

						// If we've used the credit up, remove it
						if ($credit->value == $credit->claimed)
						{
							$credit->delete();
						}

						// Update the item
						$item->update([
							'paid'		=> (int) true,
							'received'	=> $item->amount,
						]);
					}
					else
					{
						// Update the item
						$item->update(['received' => $item->received + $remaining]);

						// Update the credit
						$credit->update(['claimed' => $credit->claimed + $remaining]);

						// Remove the credit
						$credit->delete();
					}

					// Get the staff appointment
					$appt = $item->appointment;

					// Add notes to the staff appointment
					$appt->update(['notes' => $appt->notes."\r\n(Code: ".$credit->code.")"]);
				}
			}
		}
	}

}