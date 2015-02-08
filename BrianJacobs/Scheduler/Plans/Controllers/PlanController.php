<?php namespace Plans\Controllers;

use View, PlanRepositoryInterface;
use Scheduler\Controllers\BaseController;

class PlanController extends BaseController {

	protected $plans;

	public function __construct(PlanRepositoryInterface $plans)
	{
		parent::__construct();

		$this->plans = $plans;
	}

	public function myPlan()
	{
		return View::make('pages.my-plan')
			->withPlan($this->plans->getUserPlan($this->currentUser));
	}

}
