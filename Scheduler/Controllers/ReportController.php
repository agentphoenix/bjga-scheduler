<?php namespace Scheduler\Controllers;

use View,
	UserRepositoryInterface;

class ReportController extends BaseController {

	protected $user;

	public function __construct(UserRepositoryInterface $user)
	{
		$this->user = $user;
	}

	public function index()
	{
		return View::make('pages.admin.reports.index');
	}

	public function monthly()
	{
		return View::make('pages.admin.reports.monthly');
	}

	public function unpaid()
	{
		return View::make('pages.admin.reports.unpaid')
			->withAmount($this->user->getUnpaidAmount())
			->withUnpaid($this->user->getUnpaid());
	}

}