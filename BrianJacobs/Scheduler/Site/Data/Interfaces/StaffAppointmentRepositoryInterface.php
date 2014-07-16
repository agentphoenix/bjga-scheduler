<?php namespace Scheduler\Data\Interfaces;

interface StaffAppointmentRepositoryInterface {

	public function find($id);
	public function getAttendees($id);
	public function getRecurringLessons($id = false);
	public function getUpcomingEvents($days = 90);
	public function updateRecurringLesson($id, array $data);
	
}