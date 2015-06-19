<?php namespace Scheduler\Data\Repositories\Eloquent;

use Notification as Model,
	NotificationRepositoryInterface;
use Scheduler\Data\Repositories\BaseRepository;

class NotificationRepository extends BaseRepository implements NotificationRepositoryInterface {

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
