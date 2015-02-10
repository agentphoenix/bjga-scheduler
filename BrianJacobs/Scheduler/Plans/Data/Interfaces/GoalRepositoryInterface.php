<?php namespace Plans\Data\Interfaces;

use UserModel as User;
use Scheduler\Data\Interfaces\BaseRepositoryInterface;

interface GoalRepositoryInterface extends BaseRepositoryInterface {

	public function create(array $data);
	public function getUserGoalTimeline(User $user, $id);

}