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

		// Initial content
		$content = alert('alert-danger', "You do not have permission to add comments to this development plan.");

		if (($user->isStaff() and $user->staff->isPlanInstructor($goal->plan->id)) or ( ! $user->isStaff() and $user->plan and $user->plan->id == $goal->plan->id))
		{
			$content = view('pages.devplans.comments.create', compact('goal'));
		}

		return partial('common/modal_content', [
			'modalHeader'	=> "Add a Comment",
			'modalBody'		=> $content,
			'modalFooter'	=> false,
		]);
	}

	public function store($goalId)
	{
		// Get the goal
		$goal = $this->goalsRepo->getById($goalId, ['plan']);

		// Can the current user actually store a comment here?

		// Create the comment
		$comment = $this->repo->create(array_merge(Input::all(), ['user_id' => $this->currentUser->id]));

		// Fire the event
		event('comment.created', [$comment]);

		return redirect()->back()
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
		$content = alert('alert-danger', "You do not have permission to edit this comment.");

		if (($user->isStaff() and $user->staff->isPlanInstructor($goal->plan->id)) or ( ! $user->isStaff() and $user->plan and $user->plan->id == $goal->plan->id))
		{
			$content = view('pages.devplans.comments.edit', compact('comment'));
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
		event('comment.updated', [$comment]);

		return redirect()->back()
			->with('messageStatus', 'success')
			->with('message', "Comment updated!");
	}

	public function remove($commentId)
	{
		// Get the comment
		$comment = $this->repo->getById($commentId);

		return partial('common/modal_content', [
			'modalHeader'	=> "Remove Goal",
			'modalBody'		=> view('pages.devplans.comments.remove', compact('comment')),
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

	public function checkPermissions()
	{
		if ($this->currentUser->access() < 3)
		{
			return $this->unauthorized("You do not have permission to manage development plan goals!");
		}
	}

}
