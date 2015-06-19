<?php namespace Plans\Events;

use App;

class GoalEventHandler {

	public function onCreate($goal)
	{
		App::make('NotificationRepository')->create([
			'user_id'	=> $goal->plan->user->id,
			'type'		=> 'plan',
			'category'	=> 'goal',
			'action'	=> 'create',
			'content'	=> "\"{$goal->title}\" goal was created.",
		]);
	}

	public function onDelete($goal)
	{
		App::make('NotificationRepository')->create([
			'user_id'	=> $goal->plan->user->id,
			'type'		=> 'plan',
			'category'	=> 'goal',
			'action'	=> 'delete',
			'content'	=> "\"{$goal->title}\" goal was removed.",
		]);
	}

	public function onUpdate($goal)
	{
		App::make('NotificationRepository')->create([
			'user_id'	=> $goal->plan->user->id,
			'type'		=> 'plan',
			'category'	=> 'goal',
			'action'	=> 'update',
			'content'	=> "\"{$goal->title}\" goal was updated.",
		]);
	}

	public function onComplete($goal)
	{
		App::make('NotificationRepository')->create([
			'user_id'	=> $goal->plan->user->id,
			'type'		=> 'plan',
			'category'	=> 'goal',
			'action'	=> 'complete',
			'content'	=> "\"{$goal->title}\" goal was completed.",
		]);
	}

	public function onReOpen($goal)
	{
		App::make('NotificationRepository')->create([
			'user_id'	=> $goal->plan->user->id,
			'type'		=> 'plan',
			'category'	=> 'goal',
			'action'	=> 'reopen',
			'content'	=> "\"{$goal->title}\" goal was re-opened.",
		]);
	}

}
