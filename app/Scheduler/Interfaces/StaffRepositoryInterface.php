<?php namespace Scheduler\Interfaces;

interface StaffRepositoryInterface {

	public function all();

	public function allForDropdown($onlyInstructors = true);

	public function create(array $data);

	public function createException($id, array $data);

	public function delete($id);

	public function deleteException($id);

	public function find($id);

	public function findException($id);

	public function getAppointments();

	public function update($id, array $data);
	
}