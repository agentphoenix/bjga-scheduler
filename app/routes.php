<?php

Route::get('conflicts', function()
{
	$date = Date::now();
	$user = Auth::user();
	$service = ServiceModel::find(2);

	$a = new Scheduler\Services\AvailabilityService;
	s($a->find($user, $date, $service));
});