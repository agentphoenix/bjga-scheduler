<?php namespace Plans\Data\Presenters;

use Config, Markdown;
use Laracasts\Presenter\Presenter;

class PlanPresenter extends Presenter {

	public function conversation()
	{
		$output = '';

		if ($this->entity->conversations->count() > 0)
		{
			foreach ($this->entity->conversations as $c)
			{
				$output.= partial('common.blockquote', [
					'author'	=> $c->user->present()->name,
					'content'	=> $c->present()->content,
					'class'		=> ($c->user->isStaff()) ? ' quote-staff' : false,
				]);
			}
		}

		return $output;
	}

	public function created()
	{
		return $this->entity->created_at->format(Config::get('bjga.dates.dateNoDay'));
	}

}
