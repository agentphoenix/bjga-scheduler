<?php namespace Scheduler\Events;

use Queue;

class BookingEventHandler {

	public function createBlock($user, $appt)
	{
		Queue::push('Scheduler\Services\CalendarService', array('model' => $user));
	}

	public function createLesson($service, $staffAppt, $userAppt)
	{
		Queue::push('Scheduler\Services\CalendarService', array('model' => $staffAppt));
	}

	public function createProgram($service, $userAppt){}

	public function userCancelledLesson($service, $user)
	{
		// Send an email to the student

		// Send an email to the instructor
	}

	public function userCancelledProgram($service, $user)
	{
		# code...
	}

	public function instructorCancelledLesson($service)
	{
		# code...
	}

	public function instructorCancelledProgram($service)
	{
		# code...
	}

}