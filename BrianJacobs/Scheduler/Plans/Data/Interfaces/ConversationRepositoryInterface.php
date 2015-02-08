<?php namespace Plans\Data\Interfaces;

use Scheduler\Data\Interfaces\BaseRepositoryInterface;

interface ConversationRepositoryInterface extends BaseRepositoryInterface {

	public function create(array $data);

}