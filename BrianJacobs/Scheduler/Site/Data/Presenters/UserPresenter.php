<?php namespace Scheduler\Data\Presenters;

use Laracasts\Presenter\Presenter;

class UserPresenter extends Presenter {

	public function creditMoney()
	{
		return "$".$this->entity->getCredits()['money'];
	}

	public function creditTime()
	{
		$value = (int) $this->entity->getCredits()['time'] / 60;

		if ($value === 1)
		{
			return "{$value} hour";
		}

		return "{$value} hours";
	}

}