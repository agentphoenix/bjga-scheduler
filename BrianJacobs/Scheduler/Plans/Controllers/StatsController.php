<?php namespace Plans\Controllers;

use Input,
	GoalRepositoryInterface as GoalRepo,
	StatRepositoryInterface as StatRepo;
use Scheduler\Controllers\BaseController;

class StatsController extends BaseController {

	protected $goalsRepo;
	protected $repo;

	public function __construct(GoalRepo $goals, StatRepo $stats)
	{
		parent::__construct();

		$this->goalsRepo = $goals;
		$this->repo = $stats;
	}

	public function create($goalId)
	{
		// Get the goal
		$goal = $this->goalsRepo->getById($goalId);

		if ( ! $this->hasPermission($this->currentUser, $goal))
		{
			return $this->unauthorized("You do not have permission to create stats for this development plan.");
		}

		// Build the types
		$types = [
			'' => "Choose a stat type",
			'round' => "On-course Round",
			'practice' => "Practice Session",
			'trackman' => "TrackMan Combine",
			'tournament' => "Tournament Results",
		];

		$holes = [
			9 => '9 holes',
			18 => '18 holes',
			'other' => 'Other',
		];

		$goals = $goal->plan->activeGoals;

		return partial('common/modal_content', [
			'modalHeader'	=> "Add Stats",
			'modalBody'		=> view('pages.devplans.stats.create', compact('goal', 'types', 'holes', 'goals')),
			'modalFooter'	=> false,
		]);
	}

	public function store()
	{
		// Create the stats
		$stats = $this->repo->create(Input::except(['numHoles']));

		// Fire the event
		//event('stats.created', [$stats]);

		return redirect()->back()
			->with('messageStatus', 'success')
			->with('message', "Stats created!");
	}

	public function edit($id)
	{
		// Get the stat
		$stat = $this->repo->getById($id, ['goal']);

		// Build the types
		$types = [
			'' => "Choose a stat type",
			'round' => "On-course Round",
			'practice' => "Practice Session",
			'trackman' => "TrackMan Combine",
			'tournament' => "Tournament Results",
		];

		$holes = [
			9 => '9 holes',
			18 => '18 holes',
			'other' => 'Other',
		];

		return partial('common/modal_content', [
			'modalHeader'	=> "Edit Stats",
			'modalBody'		=> view('pages.devplans.stats.edit', compact('stat', 'types', 'holes')),
			'modalFooter'	=> false,
		]);
	}

	public function update($id)
	{
		// Update the stat
		$stat = $this->repo->update($id, Input::all());

		// Fire the event
		event('stats.updated', [$stat]);

		return redirect()->back()
			->with('messageStatus', 'success')
			->with('message', "Stats were updated.");
	}

	public function remove($statId)
	{
		// Get the stat
		$stat = $this->repo->getById($statId);

		return partial('common/modal_content', [
			'modalHeader'	=> "Remove Stats",
			'modalBody'		=> view('pages.devplans.stats.remove', compact('stat')),
			'modalFooter'	=> false,
		]);
	}

	public function destroy($id)
	{
		// Remove the stat
		$stat = $this->repo->delete($id);

		// Fire the event
		event('stats.deleted', [$stat]);

		return redirect()->back()
			->with('messageStatus', 'success')
			->with('message', "Stats were removed.");
	}

	protected function hasPermission($user, $goal)
	{
		if ($user->isStaff()) return true;

		if ( ! $user->isStaff() and $goal->plan->user_id == $user->id) return true;

		return false;
	}

}
