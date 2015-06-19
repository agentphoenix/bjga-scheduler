<?php namespace Scheduler\Data\Interfaces;

interface NotificationRepositoryInterface extends BaseRepositoryInterface {

	public function create(array $data);

}