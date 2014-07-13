<?php namespace Scheduler\Presenters;

use Laracasts\Presenter\Presenter;

class CreditPresenter extends Presenter {

	public function email()
	{
		return $this->entity->email;
	}

	public function remaining()
	{
		$remaining = (int) $this->entity->value - (int) $this->entity->claimed;

		if ($remaining === 0)
		{
			$remaining = "No ";
			$remaining.= ($this->entity->type == 'time') ? "time" : "money";
		}

		return $this->formatByType($this->entity->type, $remaining)." remaining";
	}

	public function user()
	{
		return $this->entity->user->name;
	}

	public function value()
	{
		return $this->formatByType($this->entity->type, (int) $this->entity->value);
	}

	protected function formatByType($type, $value)
	{
		if (is_string($value))
		{
			return $value;
		}

		switch ($type)
		{
			case 'time':
				return $value." hours";
			break;

			case 'money':
				return "$".$value;
			break;
		}
	}

}