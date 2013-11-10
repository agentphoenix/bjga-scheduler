<?php namespace Scheduler\Controllers;

use UserRepositoryInterface;
use StaffRepositoryInterface;
use AppointmentRepositoryInterface;

class Admin extends Base {

	public function __construct(UserRepositoryInterface $user,
			AppointmentRepositoryInterface $appointment,
			StaffRepositoryInterface $staff)
	{
		parent::__construct();

		$this->user			= $user;
		$this->staff		= $staff;
		$this->appointment	= $appointment;
	}

	public function getIndex()
	{
		$this->_view = 'admin.index';

		$this->_data->appointments = $this->staff->getAppointments();
	}

}