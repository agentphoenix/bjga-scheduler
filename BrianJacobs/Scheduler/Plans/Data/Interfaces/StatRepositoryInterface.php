<?php namespace Plans\Data\Interfaces;

use Scheduler\Data\Interfaces\BaseRepositoryInterface;

interface StatRepositoryInterface extends BaseRepositoryInterface {

	public function create(array $data);
	public function delete($id);
	public function update($id, array $data);

}