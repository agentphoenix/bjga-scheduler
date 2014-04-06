<?php namespace Scheduler\Presenters;

use Config, Markdown;
use Laracasts\Presenter\Presenter;

class ServicePresenter extends Presenter {

	public function description()
	{
		return Markdown::parse($this->entity->description);
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

					$formattedTotal = money_format('%i', $total);
					$finalTotal = str_replace(".00", "", (string)number_format($formattedTotal, 2, ".", ""));

					$output = "$".$finalTotal;
				}
				else
				{
					$month = ($entity->price * $entity->occurrences) / ($entity->occurrences / 4);

					$formattedTotal = money_format('%i', $month);
					$finalTotal = str_replace(".00", "", (string)number_format($formattedTotal, 2, ".", ""));
					
					$output = "$".$finalTotal." <small>per month</small>";
				}
			}
			elseif ($entity->occurrences > 1 and $entity->isProgram())
			{
				$total = ($entity->price * $entity->occurrences);

				$formattedTotal = money_format('%i', $total);
				$finalTotal = str_replace(".00", "", (string)number_format($formattedTotal, 2, ".", ""));

				$output = "$".$finalTotal;
			}
			else
			{
				$formattedTotal = money_format('%i', $entity->price);
				$finalTotal = str_replace(".00", "", (string)number_format($formattedTotal, 2, ".", ""));

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
		return $this->entity->user->name;
	}

	public function staffEmail()
	{
		return $this->entity->user->email;
	}

}