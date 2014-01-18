<?php namespace Scheduler\Repositories\Eloquent;

use Auth,
	Date,
	UserModel,
	UserAppointmentModel,
	UserRepositoryInterface;
use Illuminate\Support\Collection;

class UserRepository implements UserRepositoryInterface {

	public function all()
	{
		return UserModel::all();
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

	public function getNonStaff()
	{
		$users = $this->all();

		return $users->filter(function($u)
		{
			return ( ! $u->isStaff());
		})->toSimpleArray('id', 'name');
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
	
}