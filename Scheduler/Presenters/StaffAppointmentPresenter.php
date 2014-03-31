<?php namespace Scheduler\Presenters;

use Config;
use Laracasts\Presenter\Presenter;

class StaffAppointmentPresenter extends Presenter {

	public function appointmentDate()
	{
		return $this->entity->start->format(Config::get('bjga.dates.date'));
	}

	public function appointmentTime()
	{
		return $this->entity->start->format(Config::get('bjga.dates.time'))." - ".$this->entity->end->format(Config::get('bjga.dates.time'));
	}

	public function appointment()
	{
		return $this->entity->start->format(Config::get('bjga.dates.date')).", ".$this->entity->start->format(Config::get('bjga.dates.time'))." - ".$this->entity->end->format(Config::get('bjga.dates.time'));
	}

	public function start()
	{
		return $this->entity->start->format(Config::get('bjga.dates.full'));
	}

}