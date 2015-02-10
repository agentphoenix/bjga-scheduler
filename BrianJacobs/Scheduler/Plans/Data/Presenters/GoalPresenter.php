<?php namespace Plans\Data\Presenters;

use Config, Markdown;
use Laracasts\Presenter\Presenter;

class GoalPresenter extends Presenter {

	public function completedDate()
	{
		return $this->entity->completed_date->format(Config::get('bjga.dates.dateNoDay'));
	}

	public function created()
	{
		return $this->entity->created_at->format(Config::get('bjga.dates.dateNoDay'));
	}

	public function summary()
	{
		return Markdown::parse($this->entity->summary);
	}

}
