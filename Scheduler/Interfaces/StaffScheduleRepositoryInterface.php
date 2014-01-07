<?php namespace Scheduler\Interfaces;

interface StaffScheduleRepositoryInterface {

	public function findTimeBlock($availability, $service);
	public function getAvailability($staffId, $date, $service);

}