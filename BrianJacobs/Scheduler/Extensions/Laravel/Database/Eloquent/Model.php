<?php namespace Scheduler\Extensions\Laravel\Database\Eloquent;

use Date,
	Config;
use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel {
	
	/*
	|--------------------------------------------------------------------------
	| Eloquent Model Method Overrides
	|--------------------------------------------------------------------------
	*/

	protected $dates = array();

	public function __construct(array $attributes = array())
	{
		$attributes = $this->scrubInputData($attributes);

		parent::__construct($attributes);
	}

	/**
	 * Get the attributes that should be converted to dates.
	 *
	 * @return array
	 */
	public function getDates()
	{
		return $this->dates;
	}

	/**
	 * Get a fresh timestamp for the model.
	 *
	 * We override this method from the Eloquent model so that we can ensure
	 * that every timestamp being generated is done so as UTC.
	 *
	 * @return mixed
	 */
	public function freshTimestamp()
	{
		return Date::now(Config::get('app.timezone'));
	}

	/**
	 * Return a timestamp as DateTime object.
	 *
	 * We override this method from the Eloquent model so that we can ensure
	 * that everything being stored in the database is being done so as UTC.
	 *
	 * @param	mixed	The value to store
	 * @return	Date
	 */
	protected function asDateTime($value)
	{
		if ( ! $value instanceof Date)
		{
			$format = $this->getDateFormat();

			return Date::createFromFormat($format, $value, Config::get('app.timezone'));
		}

		return $value;
	}

	/*
	|--------------------------------------------------------------------------
	| Model Helpers
	|--------------------------------------------------------------------------
	*/

	/**
	 * Scrub the data being used to make sure we're can store it in the table.
	 *
	 * @param	array	Array of data to scrub
	 * @return	array
	 */
	protected function scrubInputData(array $data)
	{
		// Loop through the data and scrub it for any issues
		foreach ($data as $key => $value)
		{
			// Make sure we're only using fillable fields
			if ( ! $this->isFillable($key))
			{
				unset($data[$key]);
			}
		}

		return $data;
	}

	/**
	 * Kick off a new query.
	 *
	 * @return	Builder
	 */
	public static function startQuery()
	{
		// Get a new instance of the model
		$instance = new static;

		return $instance->newQuery();
	}

	/*
	|--------------------------------------------------------------------------
	| Model Scopes
	|--------------------------------------------------------------------------
	*/

	/**
	 * Ascending order scope.
	 *
	 * @param	Builder		The query builder
	 * @param	string		The field to order by
	 * @return	void
	 */
	public function scopeOrderAsc($query, $orderBy)
	{
		$this->orderScope($query, $orderBy, 'asc');
	}

	/**
	 * Descending order scope.
	 *
	 * @param	Builder		The query builder
	 * @param	string		The field to order by
	 * @return	void
	 */
	public function scopeOrderDesc($query, $orderBy)
	{
		$this->orderScope($query, $orderBy, 'desc');
	}

	/**
	 * Do the ordering.
	 *
	 * @param	Builder		Query Builder object
	 * @param	mixed		A string or array of strings of columns
	 * @param	string		The direction to order
	 * @return	void
	 */
	protected function orderScope($query, $column, $direction)
	{
		if (is_array($column))
		{
			foreach ($column as $col)
			{
				if (in_array($col, static::$properties))
				{
					$query->orderBy($col, $direction);
				}
			}
		}
		else
		{
			if (in_array($column, static::$properties))
			{
				$query->orderBy($column, $direction);
			}
		}
	}

	/**
	 * Grouping scope.
	 *
	 * @param	Builder		The query builder
	 * @param	string		The field to group by
	 * @return	void
	 */
	public function scopeGroup($query, $groupBy)
	{
		if (in_array($groupBy, static::$properties))
		{
			$query->groupBy($groupBy);
		}
	}

}