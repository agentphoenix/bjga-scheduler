<?php namespace Scheduler\Controllers;

use View,
	UserRepositoryInterface,
	StaffRepositoryInterface,
	AppointmentRepositoryInterface;

class AdminController extends BaseController {

	public function __construct(UserRepositoryInterface $user,
			AppointmentRepositoryInterface $appointment,
			StaffRepositoryInterface $staff)
	{
		parent::__construct();

		$this->user			= $user;
		$this->staff		= $staff;
		$this->appointment	= $appointment;
	}

	public function index()
	{
		return View::make('pages.admin.index')
			->with('appointments', $this->staff->getAppointments());
	}

}