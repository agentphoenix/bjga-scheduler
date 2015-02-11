<?php namespace Plans\Controllers;

use View,
	GoalRepositoryInterface,
	PlanRepositoryInterface,
	UserRepositoryInterface;
use Scheduler\Controllers\BaseController;

class PlanController extends BaseController {

	protected $goals;
	protected $plans;
	protected $users;

	public function __construct(PlanRepositoryInterface $plans,
			GoalRepositoryInterface $goals, UserRepositoryInterface $users)
	{
		parent::__construct();

		$this->goals = $goals;
		$this->plans = $plans;
		$this->users = $users;
	}

	public function show($id = false)
	{
		if ($id)
		{
			if ( ! $this->currentUser->isStaff())
			{
				return $this->unauthorized("You do not have permission to see development plans for other students!");
			}
			elseif ($this->currentUser->isStaff() and $this->currentUser->access() == 1)
			{
				return $this->unauthorized("You do not have access to the development plan feature yet.");
			}

			// Get the user
			$user = $this->users->find($id);
		}
		else
		{
			// Get the user
			$user = $this->currentUser;
		}

		return View::make('pages.plan.show')
			->withPlan($this->plans->getFirstBy('user_id', $user->id))
			->withTimeline($this->plans->getUserPlanTimeline($user));
	}

	public function goal($id)
	{
		return View::make('pages.plan.goal')
			->withGoal($this->goals->getById($id))
			->withTimeline($this->goals->getUserGoalTimeline($this->currentUser, $id));
	}

}
