<?php namespace Scheduler\Interfaces;

interface StaffAppointmentRepositoryInterface {

	public function find($id);
	public function getAttendees($id);
	public function getUpcomingEvents($days = 90);
	
}