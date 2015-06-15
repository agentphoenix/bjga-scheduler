<?php namespace Plans\Events;

class CommentEventHandler {

	public function onCreate($comment)
	{
		$author = ($comment->goal->plan->user->id == $comment->user->id)
			? "You"
			: $comment->user->present()->name;

		app('NotificationRepository')->create([
			'user_id'	=> $comment->goal->plan->user->id,
			'type'		=> 'plan',
			'category'	=> 'comment',
			'action'	=> 'create',
			'content'	=> "{$author} commented on the \"{$comment->goal->title}\" goal.",
		]);
	}

	public function onDelete($comment)
	{
		app('NotificationRepository')->create([
			'user_id'	=> $comment->goal->plan->user->id,
			'type'		=> 'plan',
			'category'	=> 'comment',
			'action'	=> 'delete',
			'content'	=> "{$comment->user->present()->name} removed a comment on the \"{$comment->goal->title}\" goal.",
		]);
	}

	public function onUpdate($comment)
	{
		app('NotificationRepository')->create([
			'user_id'	=> $comment->goal->plan->user->id,
			'type'		=> 'plan',
			'category'	=> 'comment',
			'action'	=> 'update',
			'content'	=> "{$comment->user->present()->name} updated a comment on the \"{$comment->goal->title}\" goal.",
		]);
	}

}
