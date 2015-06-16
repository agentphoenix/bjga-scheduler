<?php namespace Plans\Controllers;

use Input,
	GoalRepositoryInterface as GoalRepo,
	CommentRepositoryInterface as CommentRepo;
use Scheduler\Controllers\BaseController;

class CommentController extends BaseController {

	protected $repo;
	protected $goalsRepo;

	public function __construct(CommentRepo $repo, GoalRepo $goals)
	{
		parent::__construct();

		$this->repo = $repo;
		$this->goalsRepo = $goals;
	}

	public function create($goalId)
	{
		// Get the goal
		$goal = $this->goalsRepo->getById($goalId, ['plan']);

		// Get the current user (for brevity)
		$user = $this->currentUser;

		$message = ($user->isStaff() or ! $user->isStaff() and $user->id == $goal->plan->user_id)
			? view('pages.devplans.comments.create', compact('goal'))
			: alert('alert-danger', "You do not have permission to create comments on this development plan.");

		return partial('common/modal_content', [
			'modalHeader'	=> "Add a Comment",
			'modalBody'		=> $message,
			'modalFooter'	=> false,
		]);
	}

	public function store($goalId)
	{
		// Get the goal
		$goal = $this->goalsRepo->getById($goalId, ['plan']);

		if ($this->currentUser->isStaff() or ! $this->currentUser->isStaff() and $this->currentUser->id == $goal->plan->user_id)
		{
			// Create the comment
			$comment = $this->repo->create(array_merge(Input::all(), ['user_id' => $this->currentUser->id]));

			// Fire the event
			event('comment.created', [$comment]);

			return redirect()->back()
				->with('messageStatus', 'success')
				->with('message', "Comment added!");
		}

		return $this->unauthorized("You do not have permission to create comments for this development plan.");
	}

	public function edit($commentId)
	{
		// Get the comment
		$comment = $this->repo->getById($commentId, ['goal']);

		// Get the current user (for brevity)
		$user = $this->currentUser;

		$message = ($user->isStaff() or ! $user->isStaff() and $user->id == $comment->user_id)
			? view('pages.devplans.comments.edit', compact('comment'))
			: alert('alert-danger', "You do not have permission to edit this comment.");

		return partial('common/modal_content', [
			'modalHeader'	=> "Edit Comment",
			'modalBody'		=> $message,
			'modalFooter'	=> false,
		]);
	}

	public function update($commentId)
	{
		// Update the comment
		$comment = $this->repo->update($commentId, Input::all());

		// Fire the event
		event('comment.updated', [$comment]);

		return redirect()->back()
			->with('messageStatus', 'success')
			->with('message', "Comment updated!");
	}

	public function remove($commentId)
	{
		// Get the comment
		$comment = $this->repo->getById($commentId);

		// Get the current user (for brevity)
		$user = $this->currentUser;

		$message = ($user->isStaff() or ! $user->isStaff() and $user->id == $comment->user_id)
			? view('pages.devplans.comments.remove', compact('comment'))
			: alert('alert-danger', "You do not have permission to remove this comment.");

		return partial('common/modal_content', [
			'modalHeader'	=> "Remove Comment",
			'modalBody'		=> $message,
			'modalFooter'	=> false,
		]);
	}

	public function destroy($commentId)
	{
		// Remove the comment
		$comment = $this->repo->delete($commentId);

		// Fire the event
		event('comment.deleted', [$comment]);

		return redirect()->back()
			->with('messageStatus', 'success')
			->with('message', "Comment was removed.");
	}

}
