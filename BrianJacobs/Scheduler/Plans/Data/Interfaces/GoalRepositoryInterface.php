<?php namespace Plans\Data\Interfaces;

use Plan, UserModel as User;
use Scheduler\Data\Interfaces\BaseRepositoryInterface;

interface GoalRepositoryInterface extends BaseRepositoryInterface {

	public function create(array $data);
	public function delete($id);
	public function getUserGoalTimeline(Plan $plan, $goalId);
	public function update($id, array $data);

}