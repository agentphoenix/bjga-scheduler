<?php namespace Scheduler\Repositories\Eloquent;

use Auth,
	Date,
	StaffModel,
	StaffRepositoryInterface;

class StaffRepository implements StaffRepositoryInterface {

	/**
	 * Get all staff members.
	 *
	 * @return	Collection
	 */
	public function all()
	{
		return StaffModel::all();
	}

	/**
	 * Get all staff members formatted for use in a dropdown.
	 *
	 * @param	bool	$onlyInstructors
	 * @return	array
	 */
	public function allForDropdown($onlyInstructors = true)
	{
		// Get all the staff members
		$all = $this->all();

		// Start an array for holding the staff
		$staff = array();

		if ($all->count() > 0)
		{
			// If we only want instructors, filter out everyone else
			if ($onlyInstructors)
			{
				$all = $all->filter(function($s)
				{
					return (bool) $s->instruction === true;
				});
			}

			// For the dropdowns, we only need staff ID and user name
			foreach ($all as $a)
			{
				$staff[$a->id] = $a->user->name;
			}
		}

		return $staff;
	}

	/**
	 * Create a new staff member.
	 *
	 * @param	array	$data
	 * @return	StaffModel
	 */
	public function create(array $data)
	{
		// Create the staff member
		$staff = StaffModel::create($data);

		// Now create a blank schedule for the staff member
		for ($d = 0; $d <= 6; $d++)
		{
			$schedule = $staff->schedule()->getModel()->newInstance()
				->fill(array('day' => $d, 'availability' => ''));

			$staff->schedule()->save($schedule);
		}

		return $staff;
	}

	/**
	 * Delete a staff member.
	 *
	 * @param	int		$id
	 * @return	StaffModel
	 */
	public function delete($id)
	{
		// Get the staff member
		$staff = $this->find($id);

		if ($staff)
		{
			// First, we need to clear out their schedule so we don't have any
			// orphaned rows of data
			foreach ($staff->schedule as $schedule)
			{
				$schedule->delete();
			}

			// Now we can delete the staff member
			$delete = $staff->delete();

			// If we deleted the staff member, then return the full staff
			// member object to use in the event
			if ($delete)
				return $staff;
		}
		
		return false;
	}

	/**
	 * Get a staff member by ID.
	 *
	 * @param	int		$id
	 * @return	StaffModel
	 */
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

	/**
	 * Update a staff member.
	 *
	 * @param	int		$id
	 * @param	array	$data
	 * @return	StaffModel
	 */
	public function update($id, array $data)
	{
		// Get the staff member
		$staff = $this->find($id);

		if ($staff)
		{
			// We're updating their schedule...
			if (array_key_exists('schedule', $data))
			{
				for ($d = 0; $d <= 6; $d++)
				{
					// Get the day's schedule
					$day = $staff->schedule->filter(function($s) use ($d)
					{
						return (int) $s->day === (int) $d;
					})->first();

					// Update the record
					$day->update(array('availability' => $data['schedule'][$d]));
				}
			}

			return $staff->fill($data)->save();
		}

		return false;
	}
	
}