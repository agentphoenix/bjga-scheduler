<?php namespace Plans\Controllers\Admin;

use Date,
	View,
	Event,
	Input,
	Session,
	Redirect,
	GoalRepositoryInterface,
	StatRepositoryInterface;
use Scheduler\Controllers\BaseController;

class StatsController extends BaseController {

	protected $goals;
	protected $repo;

	public function __construct(GoalRepositoryInterface $goals,
			StatRepositoryInterface $stats)
	{
		parent::__construct();

		$this->goals = $goals;
		$this->repo = $stats;

		// Before filter to check if the user has permissions
		//$this->beforeFilter('@checkPermissions');
	}

	public function create($goalId)
	{
		// Get the goal
		$goal = $this->goals->getById($goalId);

		// Build the types
		$types = [
			'' => "Choose a stat type",
			'round' => "On-course Round",
			'practice' => "Practice Session",
			'trackman' => "TrackMan Combine",
		];

		$holes = [
			9 => '9 holes',
			18 => '18 holes',
			'other' => 'Other',
		];

		return partial('common/modal_content', [
			'modalHeader'	=> "Add Stats",
			'modalBody'		=> View::make('pages.devplans.stats.create', compact('goal', 'types', 'holes')),
			'modalFooter'	=> false,
		]);
	}

	public function store()
	{
		// Create the stats
		$stats = $this->repo->create(Input::except(['numHoles']));

		// Fire the event
		Event::fire('stats.created', [$stats]);

		return Redirect::back()
			->with('messageStatus', 'success')
			->with('message', "Stats created!");
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
