<?php

Route::get('conflicts', function()
{
	$date = Date::now();
	$user = Auth::user();
	$service = ServiceModel::find(2);

	$a = new Scheduler\Services\AvailabilityService;
	s($a->find($user, $date, $service));
});

Route::get('lists', function()
{
	$b = App::make('scheduler.bombbomb');

	s($b->lists());
});

Route::get('calendar', function()
{
	$sa = StaffAppointmentModel::find(1);

	s($sa);

	$q = Queue::push('Scheduler\Services\CalendarService', array('model' => $sa));

	s($q);
});