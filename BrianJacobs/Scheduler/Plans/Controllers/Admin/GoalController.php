<?php namespace Plans\Controllers\Admin;

use Date,
	View,
	Event,
	Input,
	Session,
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
		//$this->beforeFilter('@checkPermissions');
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

		return Redirect::back()
			->with('messageStatus', 'success')
			->with('message', "Goal was updated.");

		/*if ($this->currentUser->isStaff())
		{
			return Redirect::route('plan', [$goal->plan->user->id])
				->with('messageStatus', 'success')
				->with('message', "Goal was successfully updated!");
		}

		return Redirect::route('plan')
			->with('messageStatus', 'success')
			->with('message', "Goal was successfully updated!");*/
	}

	public function remove($id)
	{
		// Get the goal
		$goal = $this->goals->getById($id, ['plan', 'plan.user']);

		return partial('common/modal_content', [
			'modalHeader'	=> "Remove Goal",
			'modalBody'		=> View::make('pages.devplans.goals.remove', compact('goal')),
			'modalFooter'	=> false,
		]);
	}

	public function destroy($id)
	{
		// Remove the goal
		$goal = $this->goals->delete($id);

		// Fire the event
		Event::fire('goal.deleted', [$goal]);

		return Redirect::back()
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
		$goal = $this->goals->update(Input::get('goal'), $updateData);

		// Flash the message
		Session::flash('messageStatus', 'success');
		Session::flash('message', (Input::get('status') == 'complete') ? "Goal has been completed." : "Goal has been re-opened.");
	}

	public function checkPermissions()
	{
		if ($this->currentUser->access() < 3)
		{
			return $this->unauthorized("You do not have permission to manage development plan goals!");
		}
	}

}
