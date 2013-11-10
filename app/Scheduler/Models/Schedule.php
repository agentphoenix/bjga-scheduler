<?php namespace Scheduler\Models;

use Model;

class Schedule extends Model {

	protected $table = 'staff_schedules';

	protected $fillable = array(
		'staff_id', 'day', 'availability',
	);

	protected $dates = array(
		'created_at', 'updated_at',
	);
	
	protected static $properties = array(
		'id', 'staff_id', 'day', 'availability', 'created_at', 'updated_at',
	);

	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/
	
	/**
	 * Belongs To: Staff
	 */
	public function staff()
	{
		return $this->belongsTo('Staff');
	}
	
}