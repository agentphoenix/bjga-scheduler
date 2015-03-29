<?php namespace Scheduler\Data\Presenters;

use Markdown;
use Laracasts\Presenter\Presenter;

class LocationPresenter extends Presenter {

	public function address()
	{
		return Markdown::parse($this->entity->address);
	}

	public function name()
	{
		return $this->entity->name;
	}

	public function phone()
	{
		return $this->entity->phone;
	}

	public function url()
	{
		return $this->entity->url;
	}

}