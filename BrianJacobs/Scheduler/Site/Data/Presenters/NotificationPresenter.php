<?php namespace Scheduler\Data\Presenters;

use Markdown;
use Laracasts\Presenter\Presenter;

class NotificationPresenter extends Presenter {

	public function user()
	{
		return $this->entity->user->present()->name;
	}

}
