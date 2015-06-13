<?php namespace Plans\Controllers;

use Input,
	GoalRepositoryInterface as GoalRepo,
	PlanRepositoryInterface as PlanRepo,
	UserRepositoryInterface as UserRepo,
	StaffRepositoryInterface as StaffRepo;
use Scheduler\Controllers\BaseController;

class PlanController extends BaseController {

	protected $goalsRepo;
	protected $plansRepo;
	protected $usersRepo;
	protected $staffRepo;

	public function __construct(PlanRepo $plans, GoalRepo $goals, UserRepo $users, StaffRepo $staff)
	{
		parent::__construct();

		$this->goalsRepo = $goals;
		$this->plansRepo = $plans;
		$this->usersRepo = $users;
		$this->staffRepo = $staff;
	}

	public function index()
	{
		if ( ! $this->currentUser->isStaff())
		{
			return $this->unauthorized("You do not have permission to manage development plans.");
		}

		// Get all the development plans
		$plans = ($this->currentUser->access() == 4)
			? $this->plansRepo->all(['user', 'activeGoals', 'instructors', 'instructors.user'])
			: $this->plansRepo->getInstructorPlans($this->currentUser->staff);

		// Can we create more plans?
		$createAllowed = ($plans->count() < 4);

		return view('pages.devplans.plans.index', compact('plans', 'createAllowed'));
	}

	public function show($userId = false)
	{
		if ($userId)
		{
			if ( ! $this->currentUser->isStaff() and $this->currentUser->id != $userId)
			{
				if ($this->currentUser->plan)
					return redirect()->route('plan', [$this->currentUser->id]);

				return $this->unauthorized("You don't have permission to see development plans for other students!");
			}

			// Get the user
			$user = $this->usersRepo->find($userId);
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
			$timeline = $this->plansRepo->getUserPlanTimeline($plan);

			return view('pages.devplans.plan', compact('plan', 'timeline', 'userId', 'user'));
		}

		return $this->errorNotFound("This user does not have a development plan.");
	}

	public function create()
	{
		// Get all the users
		$users[''] = "Please select a user";
		$users += $this->usersRepo->withoutDevelopmentPlan();

		$message = ($this->currentUser->isStaff())
			? view('pages.devplans.plans.create', compact('users'))
			: alert('alert-danger', "You do not have permission to create development plans.");

		return partial('common/modal_content', [
			'modalHeader'	=> "Add Development Plan",
			'modalBody'		=> $message,
			'modalFooter'	=> false,
		]);
	}

	public function store()
	{
		if ( ! $this->currentUser->isStaff())
		{
			return $this->unauthorized("You do not have permission to create development plans.");
		}

		// Create the plan
		$plan = $this->plansRepo->create(Input::all(), $this->currentUser->staff);

		// Fire the event
		event('plan.created', [$plan]);

		return redirect()->route('plan.index')
			->with('messageStatus', 'success')
			->with('message', "Development plan created!");
	}

	public function edit($id)
	{
		// Get the plan
		$plan = $this->plansRepo->getById($id, ['user', 'instructors', 'instructors.user']);

		// Get all the instructors
		$staff[''] = "Please select an instructor";
		$staff += $this->staffRepo->allForDropdown();

		foreach ($plan->instructors as $instructor)
		{
			unset($staff[$instructor->id]);
		}

		$message = ($this->currentUser->isStaff())
			? view('pages.devplans.plans.instructors', compact('plan', 'staff'))
			: alert('alert-danger', "You do not have permission to edit development plans.");

		return partial('common/modal_content', [
			'modalHeader'	=> "Add Instructor to Development Plan",
			'modalBody'		=> $message,
			'modalFooter'	=> false,
		]);
	}

	public function update($id)
	{
		if ( ! $this->currentUser->isStaff())
		{
			return $this->unauthorized("You do not have permission to edit development plans.");
		}

		// Update the plan
		$plan = $this->plansRepo->addInstructor($id, Input::get('instructor'));

		// Fire the event
		event('plan.updated', [$plan, Input::get('instructor')]);

		return redirect()->route('plan.index')
			->with('messageStatus', 'success')
			->with('message', "Instructor added to development plan!");
	}

	public function remove($id)
	{
		// Get the plan
		$plan = $this->plansRepo->getById($id, ['user']);

		$message = ($this->currentUser->isStaff())
			? view('pages.devplans.plans.remove', compact('plan'))
			: alert('alert-danger', "You do not have permission to remove development plans.");

		return partial('common/modal_content', [
			'modalHeader'	=> "Remove Development Plan",
			'modalBody'		=> $message,
			'modalFooter'	=> false,
		]);
	}

	public function destroy($id)
	{
		if ( ! $this->currentUser->isStaff())
		{
			return $this->unauthorized("You do not have permission to remove development plans.");
		}

		// Remove the plan
		$plan = $this->plansRepo->delete($id);

		// Fire the event
		event('plan.deleted', [$plan]);

		return redirect()->route('plan.index')
			->with('messageStatus', 'success')
			->with('message', "Development plan removed!");
	}

	public function removeInstructor()
	{
		if ( ! $this->currentUser->isStaff())
		{
			return $this->unauthorized("You do not have permission to edit development plans.");
		}

		// Remove the instructor
		$this->plansRepo->removeInstructor(Input::get('plan'), Input::get('instructor'));

		return json_encode(['code' => 1]);
	}

}
