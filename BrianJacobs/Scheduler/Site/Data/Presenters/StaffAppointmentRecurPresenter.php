<?php namespace Scheduler\Data\Presenters;

use Config;
use Laracasts\Presenter\Presenter;

class StaffAppointmentRecurPresenter extends Presenter {

	public function serviceName()
	{
		return $this->entity->staffAppointments->first()->service->name;
	}

	public function startDate()
	{
		return $this->entity->staffAppointments->sortBy(function($s)
		{
			return $s->start;
		})->first()->start->format(Config::get('bjga.dates.date'));
	}

	public function startTime()
	{
		return $this->entity->staffAppointments->sortBy(function($s)
		{
			return $s->start;
		})->first()->start->format(Config::get('bjga.dates.time'));
	}

	public function endDate()
	{
		return $this->entity->staffAppointments->sortBy(function($s)
		{
			return $s->start;
		})->last()->start->format(Config::get('bjga.dates.date'));
	}

	public function userName()
	{
		return $this->entity->userAppointments->first()->user->name;
	}

	public function userEmail()
	{
		return $this->entity->userAppointments->first()->user->email;
	}

	public function staffAppointments()
	{
		return $this->entity->staffAppointments->sortBy(function($s)
		{
			return $s->start;
		});
	}

	public function instructor()
	{
		return $this->entity->staff->user->present()->name;
	}

}