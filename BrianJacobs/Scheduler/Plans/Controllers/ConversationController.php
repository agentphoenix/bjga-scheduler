<?php namespace Plans\Controllers;

use View,
	Event,
	Input,
	Redirect,
	GoalRepositoryInterface,
	ConversationRepositoryInterface;
use Scheduler\Controllers\BaseController;

class ConversationController extends BaseController {

	protected $repo;
	protected $goals;

	public function __construct(ConversationRepositoryInterface $repo,
			GoalRepositoryInterface $goals)
	{
		parent::__construct();

		$this->repo = $repo;
		$this->goals = $goals;

		// Before filter to check if the user has permissions
		//$this->beforeFilter('@checkPermissions');
	}

	public function create($goalId)
	{
		// Get the goal
		$goal = $this->goals->getById($goalId, ['plan']);

		// Get the current user (for brevity)
		$user = $this->currentUser;

		// Initial content
		$content = alert('alert-danger', "You do not have permission to add comments to this development plan.");

		if (($user->isStaff() and $user->staff->isPlanInstructor($goal->plan->id)) or ( ! $user->isStaff() and $user->plan and $user->plan->id == $goal->plan->id))
		{
			$content = View::make('pages.devplans.conversations.create', compact('goal'));
		}

		return partial('common/modal_content', [
			'modalHeader'	=> "Add to the Conversation",
			'modalBody'		=> $content,
			'modalFooter'	=> false,
		]);
	}

	public function store($goalId)
	{
		// Get the goal
		$goal = $this->goals->getById($goalId, ['plan']);

		// Can the current user actually store a comment here?

		// Create the comment
		$comment = $this->repo->create(array_merge(Input::all(), ['user_id' => $this->currentUser->id]));

		// Fire the event
		Event::fire('comment.created', [$comment]);

		return Redirect::back()
			->with('messageStatus', 'success')
			->with('message', "Comment added!");
	}

	public function edit($commentId)
	{
		// Get the comment
		$comment = $this->repo->getById($commentId, ['goal']);

		// Get the current user (for brevity)
		$user = $this->currentUser;

		// Get the goal
		$goal = $comment->goal;

		// Initial content
		$content = alert('alert-danger', "You do not have permission to add comments to this development plan.");

		if (($user->isStaff() and $user->staff->isPlanInstructor($goal->plan->id)) or ( ! $user->isStaff() and $user->plan and $user->plan->id == $goal->plan->id))
		{
			$content = View::make('pages.devplans.conversations.edit', compact('comment'));
		}

		return partial('common/modal_content', [
			'modalHeader'	=> "Edit Comment",
			'modalBody'		=> $content,
			'modalFooter'	=> false,
		]);
	}

	public function update($commentId)
	{
		// Update the comment
		$comment = $this->repo->update($commentId, Input::all());

		// Fire the event
		Event::fire('comment.updated', [$comment]);

		return Redirect::back()
			->with('messageStatus', 'success')
			->with('message', "Comment updated!");
	}

	public function remove($id)
	{
		// Get the comment
		$comment = $this->repo->getById($id);

		return partial('common/modal_content', [
			'modalHeader'	=> "Remove Goal",
			'modalBody'		=> View::make('pages.devplans.conversations.remove', compact('comment')),
			'modalFooter'	=> false,
		]);
	}

	public function destroy($id)
	{
		// Remove the comment
		$comment = $this->repo->delete($id);

		// Fire the event
		Event::fire('comment.deleted', [$comment]);

		return Redirect::back()
			->with('messageStatus', 'success')
			->with('message', "Comment was removed.");
	}

	public function checkPermissions()
	{
		if ($this->currentUser->access() < 3)
		{
			return $this->unauthorized("You do not have permission to manage development plan goals!");
		}
	}

}
