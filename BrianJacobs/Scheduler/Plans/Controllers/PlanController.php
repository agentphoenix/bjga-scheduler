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

	public function show($userId = false)
	{
		if ($userId)
		{
			if ( ! $this->currentUser->isStaff() and $this->currentUser->id != $userId)
			{
				return $this->unauthorized("You don't have permission to see development plans for other students!");
			}

			// Get the user
			$user = $this->users->find($userId);
		}
		else
		{
			// Get the user
			$user = $this->currentUser;

			// Set the user ID
			$userId = $user->id;
		}

		// Load the plan from the user object
		$user = $user->load('plan');

		// Get the plan
		$plan = $user->plan;

		if ($plan)
		{
			// Get the plan timeline
			$timeline = $this->plans->getUserPlanTimeline($plan);

			return View::make('pages.devplans.plan', compact('plan', 'timeline', 'userId', 'user'));
		}

		return $this->errorNotFound("This user does not have a development plan.");
	}

	public function goal($userId = false, $goalId)
	{
		if ($userId)
		{
			if ( ! $this->currentUser->isStaff() and $this->currentUser->id != $userId)
			{
				return $this->unauthorized("You don't have permission to see development plans for other students!");
			}

			// Get the user
			$user = $this->users->find($userId);
		}
		else
		{
			// Get the user
			$user = $this->currentUser;
		}

		// Load the plan and goals from the user object
		$user = $user->load('plan');

		// Get the goal
		$goal = $this->goals->getById($goalId);

		if ( ! $goal)
		{
			//
		}

		// Get the goal timeline
		$timeline = $this->goals->getUserGoalTimeline($user->plan, $goalId);

		return View::make('pages.devplans.goal', compact('goal', 'timeline', 'userId', 'user'));
	}

}
