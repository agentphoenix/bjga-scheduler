<?php namespace Plans\Data\Interfaces;

use Plan, UserModel as User;
use Scheduler\Data\Interfaces\BaseRepositoryInterface;

interface GoalRepositoryInterface extends BaseRepositoryInterface {

	public function create(array $data);
	public function getUserGoalTimeline(Plan $plan, $goalId);

}