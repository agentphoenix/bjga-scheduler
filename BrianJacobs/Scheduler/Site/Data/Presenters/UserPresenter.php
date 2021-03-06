<?php namespace Scheduler\Data\Presenters;

use Laracasts\Presenter\Presenter;

class UserPresenter extends Presenter {

	public function creditMoney()
	{
		return "$".$this->entity->getCredits()['money'];
	}

	public function creditTime()
	{
		$value = (float) $this->entity->getCredits()['time'] / 60;

		if ($value == 1)
		{
			return "{$value} hour";
		}

		if ($value < 1)
		{
			return $this->entity->getCredits()['time']." minutes";
		}

		return "{$value} hours";
	}

	public function firstName()
	{
		// Break the name up into pieces
		$namePieces = explode(' ', $this->entity->name);

		if (is_array($namePieces)) return trim($namePieces[0]);
	}

}