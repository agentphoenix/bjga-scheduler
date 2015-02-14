<?php namespace Scheduler\Data\Interfaces;

interface StaffRepositoryInterface {

	public function all($onlyInstructors = false);
	public function allForDropdown($onlyInstructors = true);
	public function create(array $data);
	public function delete($id);
	public function deleteBlock($id);
	public function find($id);
	public function getAppointments();
	public function getBlocks($user);
	public function getSchedule($staffId, $days);
	public function update($id, array $data);
	public function updateSchedule($staffId, $day, $availability);
	
}