<?php namespace Scheduler\Interfaces;

interface ScheduleRepositoryInterface {

	/**
	 * Get a staff member's available time.
	 *
	 * @param	int		Staff member ID
	 * @param	string	Event date (Y-m-d)
	 * @param	Service	Service Object
	 * @param	array
	 */
	public function getAvailability($staffID, $date, $service);

	/**
	 * Figure out what time slots are available given the duration of
	 * the service the user is trying to book.
	 *
	 * @param	array		Staff member availability
	 * @param	Service		Service object
	 * @param	array
	 */
	public function findTimeBlock($availability, $service);

}