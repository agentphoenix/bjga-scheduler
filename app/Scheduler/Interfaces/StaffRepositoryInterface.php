<?php namespace Scheduler\Interfaces;

interface StaffRepositoryInterface {

	public function all();

	public function allForDropdown($onlyInstructors = true);

	public function create(array $data);

	public function delete($id);

	public function find($id);

	public function getAppointments();

	public function update($id, array $data);
	
}