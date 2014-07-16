<?php namespace Scheduler\Data\Interfaces;

use Date;

interface CreditRepositoryInterface {

	public function all();
	public function create(array $data);
	public function delete($id);
	public function find($id);
	public function findByCode($code);
	public function findByDate($field, Date $date);
	public function removeClaimed();
	public function removeExpired(Date $date, $exact = false);
	public function update($id, array $data);

}