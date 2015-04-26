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
				return $this->unauthorized("You don't have permission to see development plans for other students!");
			}
			elseif ($this->currentUser->isStaff() and $this->currentUser->access() < 3)
			{
				return $this->unauthorized("You don't have access to the development plan feature yet.");
			}

			// Get the user
			$user = $this->users->find($id);
		}
		else
		{
			// Get the user
			$user = $this->currentUser;
		}

		// Load the plan from the user object
		$user = $user->load('plan');

		// Get the plan
		$plan = $user->plan;

		// Get the plan timeline
		$timeline = $this->plans->getUserPlanTimeline($plan);

		return View::make('pages.devplans.show', compact('plan', 'timeline'));
	}

	public function goal($id)
	{
		// Get the goal
		$goal = $this->goals->getById($id);

		// Get the goal timeline
		$timeline = $this->goals->getUserGoalTimeline($this->currentUser, $id);

		return View::make('pages.devplans.goal', compact('goal', 'timeline'));
	}

}
