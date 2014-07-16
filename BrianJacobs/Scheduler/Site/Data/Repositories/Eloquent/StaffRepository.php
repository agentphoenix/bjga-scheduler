<?php namespace Scheduler\Data\Repositories\Eloquent;

use Auth,
	Date,
	StaffModel,
	StaffAppointmentModel,
	StaffRepositoryInterface;
use Illuminate\Support\Collection;

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

	public function deleteBlock($id)
	{
		return StaffAppointmentModel::destroy($id);
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

		// Get today
		$today = Date::now();

		return $user->staff->appointments->filter(function($a) use ($today)
		{
			return ($a->start->startOfDay() == $today->startOfDay());
		})->sortBy(function($a)
		{
			return $a->start;
		});
	}

	public function getBlocks($user)
	{
		// Get the staff record
		$staff = $user->staff;

		// Start an array for storing the blocks
		$blocks = array();

		if ($staff)
		{
			if ($staff->appointments->count() > 0)
			{
				// Get today
				$today = Date::now();

				return $staff->appointments->filter(function($a) use ($today)
				{
					return (int) $a->service_id === 1 and $a->start->startOfDay() >= $today->startOfDay();
				})->sortBy(function($a)
				{
					return $a->start;
				});
			}

			return new Collection;
		}

		return new Collection;
	}

	public function getSchedule($staffId, $days)
	{
		// Get the staff member
		$staff = $this->find($staffId);

		if ($staff)
		{
			// Get today
			$today = Date::now();

			return $staff->appointments->filter(function($a) use ($today, $days)
			{
				return $a->start->startOfDay() <= $today->addDays($days)->startOfDay();
			})->sortBy(function($a)
			{
				return $a->start;
			});
		}

		return new Collection;
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
			return $staff->fill($data)->save();

		return false;
	}

	public function updateSchedule($staffId, $day, $availability)
	{
		// Get the staff member
		$staff = $this->find($staffId);

		if ($staff)
		{
			// Get the day
			$daySchedule = $staff->schedule->filter(function($s) use ($day)
			{
				return (int) $s->day === (int) $day;
			})->first();

			return $daySchedule->update(array('availability' => $availability));
		}

		return false;
	}
	
}