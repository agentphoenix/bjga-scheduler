<?php namespace Scheduler\Controllers;

use View,
	UserRepositoryInterface,
	StaffRepositoryInterface,
	StaffAppointmentRepositoryInterface;

class AdminController extends BaseController {

	public function __construct(UserRepositoryInterface $user,
			StaffAppointmentRepositoryInterface $appointment,
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