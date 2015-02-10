<?php namespace Plans\Data\Interfaces;

use UserModel as User;
use Scheduler\Data\Interfaces\BaseRepositoryInterface;

interface PlanRepositoryInterface extends BaseRepositoryInterface {

	public function create(array $data);
	public function getUserPlanTimeline(User $user);

}