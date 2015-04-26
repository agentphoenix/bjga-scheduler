<?php namespace Plans\Data\Interfaces;

use UserModel as User,
	StaffModel as Staff;
use Scheduler\Data\Interfaces\BaseRepositoryInterface;

interface PlanRepositoryInterface extends BaseRepositoryInterface {

	public function addInstructor($planId, $instructorId);
	public function create(array $data, Staff $instructor);
	public function delete($id);
	public function getInstructorPlans(Staff $instructor);
	public function getUserPlanTimeline(User $user);
	public function removeInstructor($planId, $instructorId);

}