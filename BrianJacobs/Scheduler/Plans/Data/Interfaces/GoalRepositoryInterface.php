<?php namespace Plans\Data\Interfaces;

use Scheduler\Data\Interfaces\BaseRepositoryInterface;

interface GoalRepositoryInterface extends BaseRepositoryInterface {

	public function create(array $data);

}