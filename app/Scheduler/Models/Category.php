<?php namespace Scheduler\Models;

use Model;

class Category extends Model {

	protected $table = 'categories';

	protected $fillable = array(
		'name', 'description',
	);

	protected $dates = array(
		'created_at', 'updated_at',
	);
	
	protected static $properties = array(
		'id', 'name', 'description', 'created_at', 'updated_at',
	);

	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/
	
	/**
	 * Has Many: Services
	 */
	public function services()
	{
		return $this->hasMany('Service');
	}

	/*
	|--------------------------------------------------------------------------
	| Model Methods
	|--------------------------------------------------------------------------
	*/

	public static function getValues()
	{
		// Get a new instance of the model
		$instance = new static;

		// Start the new query
		$query = $instance->newQuery();

		// Get all the items
		$items = $query->get();

		// Start a holding array for the results
		$final = array();

		foreach ($items as $item)
		{
			$final[$item->id] = $item->name;
		}

		return $final;
	}
	
}