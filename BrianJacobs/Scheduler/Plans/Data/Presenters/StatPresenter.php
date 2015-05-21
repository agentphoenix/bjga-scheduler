<?php namespace Plans\Data\Presenters;

use Config, Markdown;
use Laracasts\Presenter\Presenter;

class StatPresenter extends Presenter {

	public function created()
	{
		return $this->entity->created_at->format(Config::get('bjga.dates.dateNoDay'));
	}

	public function goal()
	{
		return $this->entity->goal->present()->title;
	}

	public function header()
	{
		if ($this->entity->type == 'round') return "On-Course Round Stats";

		if ($this->entity->type == 'trackman') return "TrackMan Combine Results";
		
		if ($this->entity->type == 'practice') return "Practice Session Stats";
	}

	public function notes()
	{
		return Markdown::parse($this->entity->notes);
	}

	public function summary()
	{
		return partial('round-stats', ['stats' => $this->entity]);
	}

}
