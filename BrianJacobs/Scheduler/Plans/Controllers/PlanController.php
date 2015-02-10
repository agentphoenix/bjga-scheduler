<?php namespace Plans\Controllers;

use View,
	GoalRepositoryInterface,
	PlanRepositoryInterface;
use Scheduler\Controllers\BaseController;

class PlanController extends BaseController {

	protected $goals;
	protected $plans;

	public function __construct(PlanRepositoryInterface $plans,
			GoalRepositoryInterface $goals)
	{
		parent::__construct();

		$this->goals = $goals;
		$this->plans = $plans;
	}

	public function myPlan()
	{
		return View::make('pages.my-plan')
			->withPlan($this->plans->getFirstBy('user_id', $this->currentUser->id))
			->withTimeline($this->plans->getUserPlanTimeline($this->currentUser));
	}

	public function goal($id)
	{
		return View::make('pages.plan.goal')
			->withGoal($this->goals->getById($id))
			->withTimeline($this->goals->getUserGoalTimeline($this->currentUser, $id));
	}

}
