<?php namespace Scheduler\Events;

use Queue;

class BookingEventHandler {

	public function createLesson($service, $staffAppt, $userAppt)
	{
		Queue::push('Scheduler\Services\CalendarService', array('model' => $staffAppt));
	}

	public function createProgram($service, $userAppt){}

}