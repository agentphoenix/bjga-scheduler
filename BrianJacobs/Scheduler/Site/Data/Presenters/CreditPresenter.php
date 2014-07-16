<?php namespace Scheduler\Data\Presenters;

use Markdown;
use Laracasts\Presenter\Presenter;

class CreditPresenter extends Presenter {

	public function email()
	{
		return $this->entity->email;
	}

	public function expires()
	{
		return $this->entity->expires->format(Config::get('bjga.dates.date'));
	}

	public function claimed()
	{
		if ($this->entity->type == 'time')
		{
			return (int) $this->entity->claimed / 60;
		}

		return (int) $this->entity->claimed;
	}

	public function notes()
	{
		return Markdown::parse($this->entity->notes);
	}

	public function remaining()
	{
		return (int) $this->value() - (int) $this->claimed();
	}

	public function remainingLong()
	{
		$remaining = $this->remaining();

		if ($remaining === 0)
		{
			$remaining = "No {$this->entity->type}";
		}

		return $this->formatByType($this->entity->type, $remaining)." remaining";
	}

	public function user()
	{
		return $this->entity->user->name;
	}

	public function value()
	{
		if ($this->entity->type == 'time')
		{
			return (int) $this->entity->value / 60;
		}

		return (int) $this->entity->value;
	}

	public function valueLong()
	{
		return $this->formatByType($this->entity->type, $this->value());
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
				if ($value === 1)
				{
					return "{$value} hour";
				}

				return "{$value} hours";
			break;

			case 'money':
				return "$".$value;
			break;
		}
	}

}