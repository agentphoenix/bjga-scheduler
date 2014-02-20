<?php namespace Scheduler\Events;

use Queue;

class AppointmentEventHandler {

	public function onCreated()
	{
		# code...
	}
	
	public function onUpdated($staffAppt, $userAppt)
	{
		Queue::push('Scheduler\Services\CalendarService', array('model' => $staffAppt));
	}

}