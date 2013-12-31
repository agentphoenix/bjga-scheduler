<?php namespace Scheduler\Repositories;

use Auth;
use Date;
use User;
use UserRepositoryInterface;
use Illuminate\Support\Collection;

class UserRepository implements UserRepositoryInterface {

	public function all()
	{
		return User::all();
	}

	public function allPaginated()
	{
		return User::paginate(25);
	}

	public function create(array $data)
	{
		return User::create($data);
	}

	public function delete($id)
	{
		return User::destroy($id);
	}

	public function find($id)
	{
		return User::find($id);
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

	public function getUnscheduledAppointments($id = false)
	{
		// Get the user
		$user = ($id) ? $this->find($id) : Auth::user();

		if ($user)
		{
			return $user->appointments->filter(function($a)
			{
				return $a->appointment->date === null;
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
			// Get today
			$today = Date::now()->startOfDay();

			// Make sure we only show appointments from today on
			$appointments = $user->appointments->filter(function($a) use($today)
			{
				if ($a->appointment->date !== null)
				{
					$appointmentDate = Date::createFromFormat('Y-m-d', $a->appointment->date);

					return ($appointmentDate->gte($today));
				}
			})->sortBy(function($appt)
			{
				return $appt->appointment->date;
			})->sortBy(function($appt)
			{
				return $appt->appointment->start_time;
			});

			return $appointments;
		}

		return array();
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
			return $user->update($data);

		return false;
	}
	
}