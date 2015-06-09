<?php namespace Plans\Controllers;

use View,
	Event,
	Input,
	Redirect,
	GoalRepositoryInterface;
use Scheduler\Controllers\BaseController;

class LessonsController extends BaseController {

	protected $goals;
	
	public function __construct(GoalRepositoryInterface $goals)
	{
		parent::__construct();

		$this->goals = $goals;

		// Before filter to check if the user has permissions
		//$this->beforeFilter('@checkPermissions');
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
			'modalBody'		=> View::make('pages.devplans.stats.edit', compact('stat', 'types', 'holes')),
			'modalFooter'	=> false,
		]);
	}

	public function update($id)
	{
		// Update the stat
		$stat = $this->repo->update($id, Input::all());

		// Fire the event
		Event::fire('stats.updated', [$stat]);

		return Redirect::back()
			->with('messageStatus', 'success')
			->with('message', "Stats were updated.");
	}

	public function remove($id)
	{
		// Get the stat
		$stat = $this->repo->getById($id);

		return partial('common/modal_content', [
			'modalHeader'	=> "Remove Stats",
			'modalBody'		=> View::make('pages.devplans.stats.remove', compact('stat')),
			'modalFooter'	=> false,
		]);
	}

	public function destroy($id)
	{
		// Remove the stat
		$stat = $this->repo->delete($id);

		// Fire the event
		Event::fire('stats.deleted', [$stat]);

		return Redirect::back()
			->with('messageStatus', 'success')
			->with('message', "Stats were removed.");
	}

}
