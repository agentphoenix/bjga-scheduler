<?php namespace Plans\Data\Repositories;

use Plan as Model,
	UserModel as User,
	PlanRepositoryInterface;
use Scheduler\Data\Repositories\BaseRepository;

class PlanRepository extends BaseRepository implements PlanRepositoryInterface {

	protected $model;

	public function __construct(Model $model)
	{
		$this->model = $model;
	}

	public function create(array $data)
	{
		return $this->model->create($data);
	}

	public function getUserPlan(User $user)
	{
		return $user->plan->load('goals', 'conversations', 'goals.conversations', 'conversations.user');
	}

}
