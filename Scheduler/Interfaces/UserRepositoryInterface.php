<?php namespace Scheduler\Interfaces;

use UserModel;

interface UserRepositoryInterface {

	public function all();
	public function allForDropdown();
	public function allPaginated();
	public function create(array $data);
	public function delete($id);
	public function find($id);
	public function getAccessLevel($id = false);
	public function getAppointment($id, $user = false);
	public function getAppointmentRecord($id);
	public function getNonStaff();
	public function getSchedule(UserModel $user, $days = 90);
	public function getScheduleHistory(UserModel $user);
	public function getUnpaid();
	public function getUnpaidAmount();
	public function getUnscheduledAppointments($id = false);
	public function getUserSchedule($id = false);
	public function isStaff($id = false);
	public function update($id, array $data);
	
}