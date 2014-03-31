<?php namespace Scheduler\Presenters;

use Config;
use Laracasts\Presenter\Presenter;

class ServicePresenter extends Presenter {

	public function price()
	{
		// Get the entity
		$entity = $this->entity;

		if ($entity->price > 0)
		{
			if ($entity->occurrences > 1 and $entity->isLesson())
			{
				$month = ($entity->price * $entity->occurrences) / ($entity->occurrences / 4);
				
				$output = "$".round($perMonth, 2)." <small>per month</small>";
			}
			else
			{
				$output = "${$entity->price}";
			}
		}
		else
		{
			$output = "Free";
		}

		return $output;
	}

	public function staffName()
	{
		return $this->entity->user->name;
	}

	public function staffEmail()
	{
		return $this->entity->user->email;
	}

}