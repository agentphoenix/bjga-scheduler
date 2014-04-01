<?php

Route::get('calendar', function()
{
	$sa = StaffAppointmentModel::find(1);

	$q = Queue::push('Scheduler\Services\CalendarService', array('staff' => $sa->staff->id));

	return 'Done!';
});