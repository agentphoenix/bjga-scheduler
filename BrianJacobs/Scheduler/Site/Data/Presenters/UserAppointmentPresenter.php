<?php namespace Scheduler\Data\Presenters;

use Laracasts\Presenter\Presenter;

class UserAppointmentPresenter extends Presenter {

	public function total()
	{
		return $this->formatCurrency((float) $this->entity->amount);
	}

	public function due()
	{
		$remaining = (float) $this->entity->amount - (float) $this->entity->received;

		return $this->formatCurrency($remaining);
	}

	private function formatCurrency($value)
	{
		$formattedTotal = sprintf('%01.2f', $value);
		
		return '$'.str_replace(".00", "", (string)number_format($formattedTotal, 2, ".", ""));
	}

	public function location()
	{
		return $this->entity->appointment->location->present()->name;
	}

}