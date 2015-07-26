<?php namespace Plans\Data\Repositories;

use Stat as Model,
	StatRepositoryInterface;
use Scheduler\Data\Repositories\BaseRepository;

class StatRepository extends BaseRepository implements StatRepositoryInterface {

	protected $model;

	public function __construct(Model $model)
	{
		$this->model = $model;
	}

	public function create(array $data)
	{
		// An array for storing the cleaned data
		$input = [];

		// Make sure we're only using the data that was entered
		foreach ($data as $key => $value)
		{
			if (is_string($value) and strlen($value) > 0)
			{
				$input[$key] = $value;
			}
		}

		// Make sure we have goals to add it to, otherwise use the goal
		// we kicked the whole process off from as the goal to associate it with
		$goals = (isset($data['goals'])) ? $data['goals'] : [$data['goal_id']];

		foreach ($goals as $goal)
		{
			// Create the stat
			$stat = $this->model->create($input);

			// Update the goal ID
			$stat->goal_id = $goal;
			$stat->save();

			// Fire the stat creation event
			event('stats.created', [$stat]);
		}

		// TODO: need to handle stats for multiple goals
		// TODO: need to handle kicking off the event handler for multiple stats

		// We don't need the goal idea anymore
		//unset($input['goal_id']);

		// Create the stat record
		//$stat = $this->model->create($input);

		// Sync the goals up
		//$stat->goals()->sync($goals);

		//return $stat;
	}

	public function delete($id)
	{
		// Get the stat
		$stat = $this->getById($id);

		if ($stat)
		{
			$stat->delete();

			return $stat;
		}

		return false;
	}

	public function update($id, array $data)
	{
		// Get the stat
		$stat = $this->getById($id);

		if ($stat)
		{
			// An array for storing the cleaned data
			$input = [];

			foreach ($data as $key => $value)
			{
				if (strlen($value) > 0)
				{
					$input[$key] = $value;
				}
			}

			$stat->fill($input)->save();

			return $stat;
		}

		return false;
	}

}
