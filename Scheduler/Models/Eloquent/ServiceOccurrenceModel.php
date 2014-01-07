<?php namespace Scheduler\Models\Eloquent;

use Model;

class ServiceOccurrenceModel extends Model {

	protected $table = 'services_occurrences';

	protected $fillable = array('service_id', 'start', 'end');

	protected $softDelete = true;

	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/
	
	public function service()
	{
		return $this->belongsTo('ServiceModel');
	}
	
}