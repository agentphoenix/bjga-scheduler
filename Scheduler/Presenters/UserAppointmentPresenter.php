<?php namespace Scheduler\Presenters;

use Laracasts\Presenter\Presenter;

class UserAppointmentPresenter extends Presenter {

	public function total()
	{
		return "$".(int) $this->entity->amount;
	}

	public function due()
	{
		$remaining = (int) $this->entity->amount - (int) $this->entity->received;

		return '$'.$remaining;
	}

}