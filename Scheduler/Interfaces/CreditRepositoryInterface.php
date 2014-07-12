<?php namespace Scheduler\Interfaces;

interface CreditRepositoryInterface {

	public function all();
	public function create(array $data);
	public function delete($id);
	public function find($id);
	public function findByCode($code);
	public function update($id, array $data);

}