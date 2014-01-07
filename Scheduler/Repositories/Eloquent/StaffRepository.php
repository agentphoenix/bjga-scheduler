<?php namespace Scheduler\Repositories\Eloquent;

use Auth,
	Date,
	StaffModel,
	StaffScheduleModel,
	StaffRepositoryInterface;

class StaffRepository implements StaffRepositoryInterface {

	public function all()
	{
		return StaffModel::all();
	}

	public function allForDropdown($onlyInstructors = true)
	{
		$all = $this->all();

		$staff = array();

		if ($all->count() > 0)
		{
			if ($onlyInstructors)
			{
				$all = $all->filter(function($s)
				{
					return (bool) $s->instruction === true;
				});
			}

			foreach ($all as $a)
			{
				$staff[$a->id] = $a->user->name;
			}
		}

		return $staff;
	}

	public function create(array $data)
	{
		$staff = StaffModel::create($data);

		for ($d = 0; $d <= 6; $d++)
		{
			StaffScheduleModel::create(array(
				'staff_id'		=> $staff->id,
				'day'			=> $d,
				'availability'	=> '',
			));
		}

		return $staff;
	}

	public function delete($id)
	{
		return StaffModel::destroy($id);
	}

	public function find($id)
	{
		return StaffModel::find($id);
	}

	public function getAppointments()
	{
		// Get the user
		$user = Auth::user();

		return $user->staff->appointments->filter(function($a)
		{
			$date = Date::now();

			return ($a->date == $date->format('Y-m-d'));
		})->sortBy(function($a)
		{
			return $a->start_time;
		});
	}

	public function update($id, array $data)
	{
		$staff = $this->find($id);

		if ($staff)
		{
			if (array_key_exists('schedule', $data))
			{
				for ($d = 0; $d <= 6; $d++)
				{
					// Get the day schedule
					$day = $staff->schedule->filter(function($s) use($d)
					{
						return $s->day == $d;
					})->first();

					// Update the record
					$day->update(array('availability' => $data['schedule'][$d]));
				}
			}

			return $staff->update($data);
		}

		return false;
	}
	
}