<?php namespace Scheduler\Facades;

use Illuminate\Support\Facades\Facade;

class Book extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() { return 'scheduler.booking'; }

}