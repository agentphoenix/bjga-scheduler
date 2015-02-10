<?php namespace Plans\Data\Interfaces;

use Scheduler\Data\Interfaces\BaseRepositoryInterface;

interface TimelineRepositoryInterface extends BaseRepositoryInterface {

	public function create(array $data);

}