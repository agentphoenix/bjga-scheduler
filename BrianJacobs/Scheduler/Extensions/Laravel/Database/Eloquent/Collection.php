<?php namespace Scheduler\Extensions\Laravel\Database\Eloquent;

use stdClass;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class Collection extends EloquentCollection {

	/**
	 * Convert a collection to a simple object.
	 *
	 * @param	string	The column to use for the key
	 * @param	string	The column to use for the value
	 * @return	object
	 */
	public function toSimpleObject($key = 'id', $value = 'name')
	{
		$final = new stdClass;

		foreach ($this->items as $item)
		{
			$final->{$item->{$key}} = $item->{$value};
		}

		return $final;
	}

}