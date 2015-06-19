<?php namespace Scheduler\Data\Interfaces;

interface StaffAppointmentRepositoryInterface {

	public function associateLessonWithGoal(array $data);
	public function create(array $data);
	public function find($id);
	public function getAttendees($id);
	public function getRecurringLessons($id = false, $staff = false);
	public function getUpcomingEvents($days = 90);
	public function getUpcomingEventsByMonth($days = 90);
	public function updateRecurringLesson($id, array $data);
	
}