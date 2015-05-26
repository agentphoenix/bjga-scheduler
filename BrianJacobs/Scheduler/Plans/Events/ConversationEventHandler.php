<?php namespace Plans\Events;

use App;

class ConversationEventHandler {

	public function onCreate($comment)
	{
		App::make('NotificationRepository')->create([
			'user_id'	=> $comment->goal->plan->user->id,
			'type'		=> 'plan',
			'category'	=> 'comment',
			'action'	=> 'create',
			'content'	=> "{$comment->user->present()->name} commented on the \"{$comment->goal->title}\" goal.",
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
