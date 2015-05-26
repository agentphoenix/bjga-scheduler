<?php namespace Plans\Events;

use App;

class ConversationEventHandler {

	public function onCreate($comment)
	{
		$author = ($comment->goal->plan->user->id == $comment->user->id)
			? "You"
			: $comment->user->present()->name;

		App::make('NotificationRepository')->create([
			'user_id'	=> $comment->goal->plan->user->id,
			'type'		=> 'plan',
			'category'	=> 'comment',
			'action'	=> 'create',
			'content'	=> "{$author} commented on the \"{$comment->goal->title}\" goal.",
		]);
	}

	public function onDelete($comment)
	{
		App::make('NotificationRepository')->create([
			'user_id'	=> $comment->goal->plan->user->id,
			'type'		=> 'plan',
			'category'	=> 'comment',
			'action'	=> 'delete',
			'content'	=> "{$comment->user->present()->name} removed a comment on the \"{$comment->goal->title}\" goal.",
		]);
	}

	public function onUpdate($comment)
	{
		App::make('NotificationRepository')->create([
			'user_id'	=> $comment->goal->plan->user->id,
			'type'		=> 'plan',
			'category'	=> 'comment',
			'action'	=> 'update',
			'content'	=> "{$comment->user->present()->name} updated a comment on the \"{$comment->goal->title}\" goal.",
		]);
	}

}
