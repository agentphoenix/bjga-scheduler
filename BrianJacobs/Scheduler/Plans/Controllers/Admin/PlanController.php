<?php namespace Plans\Controllers\Admin;

use View,
	Event,
	Input,
	Redirect,
	PlanRepositoryInterface,
	UserRepositoryInterface,
	StaffRepositoryInterface;
use Scheduler\Controllers\BaseController;

class PlanController extends BaseController {

	protected $plans;
	protected $users;
	protected $staff;

	public function __construct(PlanRepositoryInterface $plans,
			UserRepositoryInterface $users, StaffRepositoryInterface $staff)
	{
		parent::__construct();

		$this->plans = $plans;
		$this->users = $users;
		$this->staff = $staff;

		// Before filter to check if the user has permissions
		$this->beforeFilter('@checkPermissions');
	}

	public function index()
	{
		// Get all the development plans
		$plans = ($this->currentUser->access() == 4)
			? $this->plans->all(['user', 'activeGoals', 'instructors', 'instructors.user'])
			: $this->plans->getInstructorPlans($this->currentUser->staff);

		// Can we create more plans?
		$createAllowed = ($plans->count() < 3);

		return View::make('pages.devplans.plans.index', compact('plans', 'createAllowed'));
	}

	public function create()
	{
		// Get all the users
		$users[''] = "Please select a user";
		$users += $this->users->withoutDevelopmentPlan();

		return partial('common/modal_content', [
			'modalHeader'	=> "Add Development Plan",
			'modalBody'		=> View::make('pages.devplans.plans.create', compact('users')),
			'modalFooter'	=> false,
		]);
	}

	public function store()
	{
		// Create the plan
		$plan = $this->plans->create(Input::all(), $this->currentUser->staff);

		// Fire the event
		Event::fire('plan.created', [$plan]);

		return Redirect::route('plan.index')
			->with('messageStatus', 'success')
			->with('message', "Development plan created!");
	}

	public function edit($id)
	{
		// Get the plan
		$plan = $this->plans->getById($id, ['user', 'instructors', 'instructors.user']);

		// Get all the instructors
		$staff[''] = "Please select an instructor";
		$staff += $this->staff->allForDropdown();

		foreach ($plan->instructors as $instructor)
		{
			unset($staff[$instructor->id]);
		}

		return partial('common/modal_content', [
			'modalHeader'	=> "Add Instructor to Development Plan",
			'modalBody'		=> View::make('pages.devplans.plans.instructors', compact('plan', 'staff')),
			'modalFooter'	=> false,
		]);
	}

	public function update($id)
	{
		// Update the plan
		$plan = $this->plans->addInstructor($id, Input::get('instructor'));

		// Fire the event
		Event::fire('plan.updated', [$plan, Input::get('instructor')]);

		return Redirect::route('plan.index')
			->with('messageStatus', 'success')
			->with('message', "Instructor added to development plan!");
	}

	public function remove($id)
	{
		// Get the plan
		$plan = $this->plans->getById($id, ['user']);

		return partial('common/modal_content', [
			'modalHeader'	=> "Remove Development Plan",
			'modalBody'		=> View::make('pages.devplans.plans.remove', compact('plan')),
			'modalFooter'	=> false,
		]);
	}

	public function destroy($id)
	{
		// Remove the plan
		$plan = $this->plans->delete($id);

		// Fire the event
		Event::fire('plan.deleted', [$plan]);

		return Redirect::route('plan.index')
			->with('messageStatus', 'success')
			->with('message', "Development plan removed!");
	}

	public function removeInstructor()
	{
		// Remove the instructor
		$this->plans->removeInstructor(Input::get('plan'), Input::get('instructor'));

		return json_encode(['code' => 1]);
	}

	public function checkPermissions()
	{
		if ($this->currentUser->access() < 3)
		{
			return $this->unauthorized("You do not have permission to manage development plans!");
		}
	}

}
