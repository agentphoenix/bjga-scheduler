<?php namespace Scheduler\Controllers;

use Log,
	Auth,
	View,
	Request,
	Controller;

abstract class BaseController extends Controller {

	protected $currentUser;
	protected $layout = 'layouts.master';
	protected $request;

	public function __construct()
	{
		$this->currentUser	= (Auth::check())
			? Auth::user()->load('credits', 'staff')
			: false;
		$this->request		= Request::instance();

		//Auth::user()->load('appointments', 'appointments.appointment', 'appointments.appointment.service', 'credits', 'staff', 'staff.appointments', 'staff.appointments.userAppointments', 'staff.appointments.service', 'staff.appointments.recur', 'staff.appointments.occurrence', 'staff.appointments.userAppointments.user', 'staff.appointments.service.serviceOccurrences')
	}

	protected function unauthorized($message = false)
	{
		Log::error("{$this->currentUser->name} attempted to access {$this->request->fullUrl()}");

		if ($message)
			return View::make('pages.admin.error')->withError($message);
	}

	protected function errorNotFound($message = false)
	{
		Log::error("{$this->currentUser->name} attempted to access {$this->request->fullUrl()}");

		if ($message)
			return View::make('pages.admin.error')->withError($message);
	}

}