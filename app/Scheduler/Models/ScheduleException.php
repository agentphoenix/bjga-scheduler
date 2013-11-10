<?php namespace Scheduler\Models;

use Model;

class ScheduleException extends Model {

	protected $table = 'staff_schedules_exceptions';

	protected $fillable = array(
		'staff_id', 'date', 'exceptions',
	);

	protected $dates = array(
		'created_at', 'updated_at',
	);
	
	protected static $properties = array(
		'id', 'staff_id', 'date', 'exceptions', 'created_at', 'updated_at',
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

	/*
	|--------------------------------------------------------------------------
	| Model Accessors
	|--------------------------------------------------------------------------
	*/

	public function setExceptionsAttribute($value)
	{
		// Convert the duration from hours to minutes
		$this->attributes['exceptions'] = implode(',', $value);
	}
	
	public function getExceptionsAttribute($value)
	{
		return explode(',', $value);
	}
	
}