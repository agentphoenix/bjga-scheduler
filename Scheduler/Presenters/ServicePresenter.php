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
				
				$output = "$".money_format('%i', $perMonth)." <small>per month</small>";
			}
			elseif ($entity->occurrences > 1 and $entity->isProgram())
			{
				$total = ($entity->price * $entity->occurrences);

				$output = "$".money_format('%i', $total);
			}
			else
			{
				$output = "$".money_format('%i', $entity->price);
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