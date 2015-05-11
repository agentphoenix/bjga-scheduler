<?php namespace Scheduler\Data\Interfaces;

use Date,
	UserModel as User;

interface CreditRepositoryInterface {

	public function all();
	public function allPaginated(User $user);
	public function cleanupMalformedCredits();
	public function create(array $data);
	public function delete($id);
	public function find($id);
	public function findByCode($code);
	public function findByDate($field, Date $date);
	public function findByEmail($email);
	public function removeClaimed();
	public function removeExpired(Date $date, $exact = false);
	public function search($term);
	public function update($id, array $data);

}