<?php namespace Plans\Controllers;

use Date,
	Input,
	Session,
	UserModel as User,
	GoalRepositoryInterface as GoalRepo,
	PlanRepositoryInterface as PlanRepo,
	UserRepositoryInterface as UserRepo;
use Scheduler\Controllers\BaseController;

class GoalController extends BaseController {

	protected $goalsRepo;
	protected $usersRepo;
	protected $plansRepo;

	public function __construct(GoalRepo $goals, UserRepo $users, PlanRepo $plans)
	{
		parent::__construct();

		$this->goalsRepo = $goals;
		$this->usersRepo = $users;
		$this->plansRepo = $plans;
	}

	public function show($userId = false, $goalId)
	{
		if ($userId)
		{
			if ( ! $this->currentUser->isStaff() and $this->currentUser->id != $userId)
			{
				if ($this->currentUser->plan)
				{
					return redirect()->route('plan', [$this->currentUser->id]);
				}

				return $this->unauthorized("You do not have permission to see development plans for other students.");
			}

			// Get the user
			$user = ( ! $this->currentUser->isStaff() and $this->currentUser->id == $userId)
				? $this->currentUser
				: $this->usersRepo->find($userId);
		}
		else
		{
			// Get the user
			$user = $this->currentUser;

			// Set the userId
			$userId = $user->id;
		}

		// Load the plan and goals from the user object
		$user = $user->load('plan');

		// Get the goal
		$goal = $this->goalsRepo->getById($goalId);

		if ( ! $goal)
		{
			return $this->errorNotFound("No such goal exists in your development plan. Please return to your ".link_to_route('plan', 'development plan', [$this->currentUser->id])." and try again.");
		}

		// Get the goal timeline
		$timeline = $this->goalsRepo->getUserGoalTimeline($user->plan, $goalId);

		return view('pages.devplans.goal', compact('goal', 'timeline', 'userId', 'user'));
	}

	public function create($planId)
	{
		// Get the plan
		$plan = $this->plansRepo->getById($planId);

		$types = [
			''				=> "Please Choose One",
			'round'			=> "On-course Round",
			'practice'		=> "Practice Session",
			'trackman'		=> "TrackMan Combine",
			'tournament'	=> "Tournament Results",
		];

		$operators = [
			'='		=> "Equal to",
			'<'		=> "Less than",
			'<='	=> "Less than or equal to",
			'>'		=> "Greater than",
			'>='	=> "Greater than or equal to",
		];

		return view('pages.devplans.goals.create', compact('plan', 'operators', 'types', 'metrics'));
	}

	public function store()
	{
		// Create the goal
		$goal = $this->goalsRepo->create(Input::all());

		// Fire the event
		event('goal.created', [$goal]);

		return redirect()->route('plan', [$goal->plan->user_id])
			->with('messageStatus', 'success')
			->with('message', "Goal created!");
	}

	public function edit($id)
	{
		// Get the goal
		$goal = $this->goalsRepo->getById($id);

		// Grab the plan
		$plan = $goal->plan;

		$types = [
			''				=> "Please Choose One",
			'round'			=> "On-course Round",
			'practice'		=> "Practice Session",
			'trackman'		=> "TrackMan Combine",
			'tournament'	=> "Tournament Results",
		];

		$operators = [
			'='		=> "Equal to",
			'<'		=> "Less than",
			'<='	=> "Less than or equal to",
			'>'		=> "Greater than",
			'>='	=> "Greater than or equal to",
		];

		return view('pages.devplans.goals.edit', compact('goal', 'plan', 'types', 'operators'));
	}

	public function update($id)
	{
		// Update the goal
		$goal = $this->goalsRepo->update($id, Input::all());

		// Fire the event
		event('goal.updated', [$goal]);

		return redirect()->route('plan', [$goal->plan->user_id])
			->with('messageStatus', 'success')
			->with('message', "Goal was updated!");
	}

	public function remove($goalId)
	{
		// Get the goal
		$goal = $this->goalsRepo->getById($goalId, ['plan', 'plan.user']);

		return partial('common/modal_content', [
			'modalHeader'	=> "Remove Goal",
			'modalBody'		=> view('pages.devplans.goals.remove', compact('goal')),
			'modalFooter'	=> false,
		]);
	}

	public function destroy($id)
	{
		// Remove the goal
		$goal = $this->goalsRepo->delete($id);

		// Fire the event
		event('goal.deleted', [$goal]);

		return redirect()->back()
			->with('messageStatus', 'success')
			->with('message', "Goal was removed.");
	}

	public function changeStatus()
	{
		$updateData = [
			'completed' => (Input::get('status') == 'complete') ? 1 : 0,
			'completed_date' => (Input::get('status') == 'complete') ? Date::now() : null
		];

		// Update the goal
		$goal = $this->goalsRepo->update(Input::get('goal'), $updateData);

		if (Input::get('status') == 'complete')
		{
			event('goal.completed', [$goal]);
		}
		else
		{
			event('goal.reopened', [$goal]);
		}

		// Flash the message
		Session::flash('messageStatus', 'success');
		Session::flash('message', (Input::get('status') == 'complete') ? "Goal has been completed." : "Goal has been re-opened.");
	}

	public function removeLessonGoalAssociation()
	{
		app('StaffAppointmentRepository')->associateLessonWithGoal([
			'lesson' => Input::get('lesson'),
			'goal' => null
		]);

		event('goal.lesson.removed');

		// Flash the message
		Session::flash('messageStatus', 'success');
		Session::flash('message', "Lesson removed from goal.");

		return json_encode([]);
	}

	protected function hasPermission(User $user, $plan)
	{
		if ($user->isStaff()) return true;

		if ( ! $user->isStaff() and $user->id == $plan->user_id) return true;

		return false;
	}

}
