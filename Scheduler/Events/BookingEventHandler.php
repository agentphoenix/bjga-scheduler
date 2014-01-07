<?php namespace Scheduler\Events;

use Queue;

class BookingEventHandler {

	public function createLesson($service, $staffAppt, $userAppt)
	{
		$this->updateCalendar($staffAppt);
	}

	public function createProgram($service, $userAppt) {}

	protected function updateCalendar($appt)
	{
		Queue::push('Scheduler\Services\CalendarService', array('model' => $appt));
	}

}