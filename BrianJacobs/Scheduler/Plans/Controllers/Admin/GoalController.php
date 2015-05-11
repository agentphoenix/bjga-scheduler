<?php namespace Plans\Controllers\Admin;

use View,
	Event,
	Input,
	Redirect,
	GoalRepositoryInterface,
	PlanRepositoryInterface,
	UserRepositoryInterface,
	StaffRepositoryInterface;
use Scheduler\Controllers\BaseController;

class GoalController extends BaseController {

	protected $goals;
	protected $users;
	protected $staff;
	protected $plans;

	public function __construct(GoalRepositoryInterface $goals,
			UserRepositoryInterface $users, StaffRepositoryInterface $staff,
			PlanRepositoryInterface $plans)
	{
		parent::__construct();

		$this->goals = $goals;
		$this->users = $users;
		$this->staff = $staff;
		$this->plans = $plans;

		// Before filter to check if the user has permissions
		$this->beforeFilter('@checkPermissions');
	}

	public function create($id)
	{
		// Get the plan
		$plan = $this->plans->getById($id);

		return partial('common/modal_content', [
			'modalHeader'	=> "Add a Goal",
			'modalBody'		=> View::make('pages.devplans.goals.create', compact('plan')),
			'modalFooter'	=> false,
		]);
	}

	public function store()
	{
		// Create the goal
		$goal = $this->goals->create(Input::all());

		// Fire the event
		Event::fire('goal.created', [$goal]);

		return Redirect::back()
			->with('messageStatus', 'success')
			->with('message', "Goal created!");
	}

	public function edit($id)
	{
		// Get the goal
		$goal = $this->goals->getById($id);

		return partial('common/modal_content', [
			'modalHeader'	=> "Edit Goal",
			'modalBody'		=> View::make('pages.devplans.goals.edit', compact('goal')),
			'modalFooter'	=> false,
		]);
	}

	public function update($id)
	{
		// Update the goal
		$goal = $this->goals->update($id, Input::all());

		// Fire the event
		Event::fire('goal.updated', [$goal]);

		if ($goal->user->id != $this->currentUser->isStaff())
		{
			return Redirect::route('plan', [$goal->plan->user_id])
				->with('messageStatus', 'success')
				->with('message', "Goal was successfully updated!");
		}

		return Redirect::route('plan')
			->with('messageStatus', 'success')
			->with('message', "Goal was successfully updated!");
	}

	public function remove($id)
	{
		// Get the plan
		$plan = $this->plans->getById($id, ['user']);

		return partial('common/modal_content', [
			'modalHeader'	=> "Remove Development Plan",
			'modalBody'		=> View::make('pages.devplans.admin.remove', compact('plan')),
			'modalFooter'	=> false,
		]);
	}

	public function destroy($id)
	{
		// Remove the plan
		$plan = $this->plans->delete($id);

		// Fire the event
		Event::fire('plan.deleted', [$plan]);

		return Redirect::route('admin.plan.index')
			->with('messageStatus', 'success')
			->with('message', "Development plan removed!");
	}

	public function checkPermissions()
	{
		if ($this->currentUser->access() < 3)
		{
			return $this->unauthorized("You do not have permission to manage development plan goals!");
		}
	}

}
