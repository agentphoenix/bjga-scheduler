<?php namespace Scheduler\Presenters;

use Laracasts\Presenter\Presenter;

class UserPresenter extends Presenter {

	public function creditMoney()
	{
		return "$".$this->entity->getCredits()['money'];
	}

	public function creditTime()
	{
		return $this->entity->getCredits()['time']." hours";
	}

}