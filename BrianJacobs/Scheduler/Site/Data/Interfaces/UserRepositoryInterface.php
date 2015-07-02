<?php namespace Scheduler\Data\Interfaces;

use UserModel as User;

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
	public function getNonInstructors();
	public function getNonStaff();
	public function getSchedule(User $user, $days = 90);
	public function getScheduleHistory(User $user);
	public function getUnpaid();
	public function getUnpaidAmount();
	public function getUnscheduledAppointments($id = false);
	public function getUnusedCredits();
	public function getUserSchedule($id = false);
	public function isStaff($id = false);
	public function update($id, array $data);
	public function withDevelopmentPlan();
	public function withoutDevelopmentPlan();
	
}