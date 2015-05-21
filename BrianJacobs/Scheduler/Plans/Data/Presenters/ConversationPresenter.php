<?php namespace Plans\Data\Presenters;

use Config, Markdown;
use Laracasts\Presenter\Presenter;

class ConversationPresenter extends Presenter {

	public function author()
	{
		return $this->entity->user->present()->name;
	}

	public function content()
	{
		return partial('common.blockquote', [
			'author'	=> $this->entity->user->present()->name,
			'content'	=> Markdown::parse($this->entity->content),
			'class'		=> ($this->entity->user->isStaff() and $this->entity->user->staff->isPlanInstructor($this->entity->goal->plan->id)) ? 'quote-staff' : false,
		]);
	}

	public function created()
	{
		return $this->entity->created_at->format(Config::get('bjga.dates.dateNoDay'));
	}

	public function goal()
	{
		return $this->entity->goal->present()->title;
	}
	
}
