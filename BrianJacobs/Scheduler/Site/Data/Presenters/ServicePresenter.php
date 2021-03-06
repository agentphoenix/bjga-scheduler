<?php namespace Scheduler\Data\Presenters;

use Config, Markdown;
use Laracasts\Presenter\Presenter;

class ServicePresenter extends Presenter {

	public function description()
	{
		return Markdown::parse($this->entity->description);
	}

	public function location()
	{
		if ($this->entity->location)
		{
			return $this->entity->location->present()->name;
		}

		return false;
	}

	public function name()
	{
		return trim($this->entity->name);
	}

	public function price()
	{
		// Get the entity
		$entity = $this->entity;

		if ($entity->price > 0)
		{
			if ($entity->occurrences > 1 and $entity->isLesson())
			{
				if ($entity->occurrences % 4 != 0)
				{
					$total = ($entity->price * $entity->occurrences);

					//$formattedTotal = money_format('%i', $total);
					$formattedTotal = sprintf('%01.2f', $total);
					$finalTotal = str_replace(".00", "", (string)number_format($formattedTotal, 2, ".", ","));

					$output = "$".$finalTotal;
				}
				else
				{
					$month = ($entity->price * $entity->occurrences) / ($entity->occurrences / 4);

					//$formattedTotal = money_format('%i', $month);
					$formattedTotal = sprintf('%01.2f', $month);
					$finalTotal = str_replace(".00", "", (string)number_format($formattedTotal, 2, ".", ","));
					
					$output = "$".$finalTotal." <small>per month</small>";
				}
			}
			elseif ($entity->occurrences > 1 and $entity->isProgram())
			{
				$total = ($entity->price * $entity->occurrences);

				//$formattedTotal = money_format('%i', $total);
				$formattedTotal = sprintf('%01.2f', $total);
				$finalTotal = str_replace(".00", "", (string)number_format($formattedTotal, 2, ".", ","));

				$output = "$".$finalTotal;
			}
			else
			{
				//$formattedTotal = money_format('%i', $entity->price);
				$formattedTotal = sprintf('%01.2f', $entity->price);
				$finalTotal = str_replace(".00", "", (string)number_format($formattedTotal, 2, ".", ","));

				$output = "$".$finalTotal;
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
		if ($this->entity->staff)
		{
			return $this->entity->staff->user->name;
		}

		return false;
	}

	public function staffEmail()
	{
		if ($this->entity->staff)
		{
			return $this->entity->staff->user->email;
		}

		return false;
	}

	public function summary()
	{
		return Markdown::parse($this->entity->summary);
	}

}