<?php namespace Scheduler\Events;

use Queue;

class BookingEventHandler {

	public function createOneToOne($service, $staffAppt, $userAppt)
	{
		$this->updateCalendar($staffAppt);
	}

	public function createOneToMany($service, $userAppt) {}

	public function createManyToMany($service, $userAppt) {}

	protected function updateCalendar($appt)
	{
		Queue::push('Scheduler\Services\CalendarService', array('appt' => $appt));
	}

}