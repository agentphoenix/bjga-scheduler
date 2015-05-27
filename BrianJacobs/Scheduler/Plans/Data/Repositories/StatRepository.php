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

		foreach ($data as $key => $value)
		{
			if ( ! empty($value))
			{
				$input[$key] = $value;
			}
		}

		return $this->model->create($input);
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
			$stat->fill($data)->save();

			return $stat;
		}

		return false;
	}

}
