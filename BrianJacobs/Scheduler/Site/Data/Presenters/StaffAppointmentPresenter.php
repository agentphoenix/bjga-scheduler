<?php namespace Scheduler\Data\Presenters;

use Config, Markdown;
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

	public function location()
	{
		if ($this->entity->location)
			return $this->entity->location->present()->name;
	}

	public function notes()
	{
		return Markdown::parse($this->entity->notes);
	}

	public function start()
	{
		return $this->entity->start->format(Config::get('bjga.dates.full'));
	}

}