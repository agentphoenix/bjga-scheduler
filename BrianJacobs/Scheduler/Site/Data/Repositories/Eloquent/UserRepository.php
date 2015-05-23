<?php namespace Scheduler\Data\Repositories\Eloquent;

use Auth,
	Date,
	UserModel,
	CreditModel,
	UserAppointmentModel,
	UserRepositoryInterface;
use Illuminate\Support\Collection;

class UserRepository implements UserRepositoryInterface {

	public function all()
	{
		return UserModel::all();
	}

	public function allForDropdown()
	{
		$users[''] = "Please select a user";
		$users += UserModel::lists('name', 'id');
		
		return $users;
	}

	public function allPaginated()
	{
		return UserModel::paginate(25);
	}

	public function create(array $data)
	{
		return UserModel::create($data);
	}

	public function delete($id)
	{
		// Get the user
		$user = $this->find($id);

		if ($user)
		{
			$delete = $user->delete();

			if ($delete)
				return $user;
		}
		
		return false;
	}

	public function find($id)
	{
		return UserModel::find($id);
	}

	public function getAccessLevel($id = false)
	{
		if ( ! $id)
			return (int) Auth::user()->staff->access;

		return $this->find($id)->staff->access;
	}

	public function getAppointment($id, $user = false)
	{
		$user = ($user) ? $this->find($user) : Auth::user();

		return $user->appointments->filter(function($a) use($id)
		{
			return $a->id == $id;
		})->first();
	}

	public function getAppointmentRecord($id)
	{
		return UserAppointmentModel::find($id);
	}

	public function getNonStaff()
	{
		$users = $this->all();

		return $users->filter(function($u)
		{
			return ( ! $u->isStaff());
		})->lists('name', 'id');
	}

	public function getUnscheduledAppointments($id = false)
	{
		// Get the user
		$user = ($id) ? $this->find($id) : Auth::user();

		if ($user)
		{
			return $user->appointments->filter(function($a)
			{
				return $a->appointment->start === null;
			});
		}

		return new Collection;
	}

	public function getUserSchedule($id = false)
	{
		// Get the user
		$user = ($id) ? $this->find($id) : Auth::user();

		if ($user)
		{
			return UserAppointmentModel::attendee($user->id)->date(Date::now())
				->orderBy('start', 'asc')->get();
		}

		return new Collection;
	}

	public function isStaff($id = false)
	{
		if ( ! $id)
			return (Auth::user()->isStaff());

		return ($this->find($id)->isStaff());
	}

	public function update($id, array $data)
	{
		$user = $this->find($id);

		if ($user)
			return $user->fill($data)->save();

		return false;
	}

	public function getUnpaid()
	{
		return UserAppointmentModel::date(Date::now()->startOfDay(), '<')
			->where('paid', (int) false)
			//->orderBy('user_appointments.created_at', 'asc')
			->get();
	}

	public function getUnpaidAmount()
	{
		// Get the unpaid records
		$unpaid = $this->getUnpaid();

		// Set the unpaid amount
		$amount = 0;

		if ($unpaid->count() > 0)
		{
			foreach ($unpaid as $u)
			{
				$amount += $u->amount;
			}
		}

		return $amount;
	}

	public function getSchedule(UserModel $user, $days = 90)
	{
		// Start an array for holding everything
		$schedule = array();

		// Get today
		$today = Date::now();

		$user = $user->load('appointments', 'appointments.appointment', 'appointments.appointment.service', 'appointments.appointment.userAppointments', 'appointments.appointment.userAppointments.user', 'appointments.appointment.userAppointments.user.plan', 'appointments.appointment.location');

		// Filter user appointments to only show today forward
		$userAppointments = $user->appointments->filter(function($a) use ($today, $days)
		{
			if ($days)
			{
				if ($a->appointment)
				{
					return $a->appointment->start->startOfDay() >= $today->startOfDay() and $a->appointment->start->endOfDay() <= $today->copy()->addDays($days)->endOfDay();
				}
			}
			else
			{
				return $a->appointment->start->startOfDay() >= $today->startOfDay();
			}
		})->sortBy(function($x)
		{
			return $x->appointment->start;
		});

		if ($userAppointments->count() > 0)
		{
			foreach ($userAppointments as $a)
			{
				// How many days until this appointment?
				$daysToEvent = $a->appointment->start->diffInDays($today);

				$schedule[$daysToEvent][] = $a;
			}
		}

		if ($user->staff)
		{
			// Filter staff appointments to only show today forward
			$staffAppointments = $user->staff->appointments->filter(function($s) use ($today, $days)
			{
				if ($days)
					return $s->start->startOfDay() >= $today->startOfDay() and $s->start->endOfDay() <= $today->copy()->addDays($days)->endOfDay();
				else
					return $s->start->startOfDay() >= $today->startOfDay();
			})->sortBy(function($x)
			{
				return $x->start;
			});
			
			if ($staffAppointments->count() > 0)
			{
				foreach ($staffAppointments as $s)
				{
					// How many days until this appointment?
					$daysToEvent = $s->start->diffInDays($today);

					$schedule[$daysToEvent][] = $s;
				}
			}
		}

		// Sort the array
		ksort($schedule);

		return $schedule;
	}

	public function getScheduleHistory(UserModel $user, $direction = 'asc')
	{
		// Eager load...
		$user = $user->load('credits', 'appointments', 'appointments.appointment', 'appointments.appointment.service');

		// Start an array for holding everything
		$schedule = array();

		// Get today
		$today = Date::now();

		if ($user->appointments->count() > 0)
		{
			foreach ($user->appointments as $a)
			{
				// How many days until this appointment?
				$timestamp = $a->appointment->start->copy()->format('U');

				$schedule[$timestamp] = $a;
			}
		}

		if ($direction == 'asc')
		{
			ksort($schedule);
		}
		else
		{
			krsort($schedule);
		}

		return $schedule;
	}

	public function getUnusedCredits()
	{
		// Get all users
		$users = $this->all();

		// Eager load the credits
		$users = $users->load('credits');

		// Array for storing the results
		$results = [];

		foreach ($users as $user)
		{
			if ($user->credits->count() > 0)
			{
				// Get the nicer version of the creidts
				$niceCredits = $user->getCredits();

				if ($niceCredits['time'] > 0)
				{
					$results['time'][] = [
						'user'		=> $user,
						'credit'	=> $this->formatByType('time', $niceCredits['time'] / 60),
					];
				}

				if ($niceCredits['money'] > 0)
				{
					$results['money'][] = [
						'user'		=> $user,
						'credit'	=> $this->formatByType('money', $niceCredits['money']),
					];
				}
			}
		}

		return $results;

		// Get all the credits
		$credits = CreditModel::with('user')->get();

		$creditsArr = [];

		if ($credits->count() > 0)
		{
			foreach ($credits as $credit)
			{
				$creditsArr[$credit->type][] = $credit;
			}
		}

		return $creditsArr;
	}

	protected function formatByType($type, $value)
	{
		if (is_string($value))
		{
			return $value;
		}

		switch ($type)
		{
			case 'time':
				if ($value == 1)
				{
					return "{$value} hour";
				}

				if ($value < 1)
				{
					return ($value * 60)." minutes";
				}

				return "{$value} hours";
			break;

			case 'money':
				return "$".$value;
			break;
		}
	}

	public function withDevelopmentPlan()
	{
		return UserModel::has('plan')->orderBy('name', 'asc')->lists('name', 'id');
	}

	public function withoutDevelopmentPlan()
	{
		return UserModel::has('plan', '=', 0)->orderBy('name', 'asc')->lists('name', 'id');
	}
	
}