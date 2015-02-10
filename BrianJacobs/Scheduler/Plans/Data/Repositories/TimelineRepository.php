<?php namespace Plans\Data\Repositories;

use Timeline as Model,
	TimelineRepositoryInterface;
use Scheduler\Data\Repositories\BaseRepository;

class TimelineRepository extends BaseRepository implements TimelineRepositoryInterface {

	protected $model;

	public function __construct(Model $model)
	{
		$this->model = $model;
	}

	public function create(array $data)
	{
		return $this->model->create($data);
	}

}
