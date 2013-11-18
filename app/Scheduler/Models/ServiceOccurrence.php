<?php namespace Scheduler\Models;

use Model;

class ServiceOccurrence extends Model {

	protected $table = 'services_occurrences';

	protected $softDelete = true;

	protected $fillable = array(
		'service_id', 'date', 'start_time', 'end_time',
	);

	protected $dates = array(
		'created_at', 'updated_at', 'deleted_at',
	);
	
	protected static $properties = array(
		'id', 'service_id', 'date', 'start_time', 'end_time', 'created_at', 
		'updated_at', 'deleted_at',
	);

	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/
	
	/**
	 * Belongs To: Category
	 */
	public function service()
	{
		return $this->belongsTo('Service');
	}
	
}