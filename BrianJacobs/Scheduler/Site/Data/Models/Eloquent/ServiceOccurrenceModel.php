<?php namespace Scheduler\Data\Models\Eloquent;

use Model;

class ServiceOccurrenceModel extends Model {

	protected $table = 'services_occurrences';

	protected $fillable = array('service_id', 'start', 'end');

	protected $softDelete = true;

	protected $dates = array('start', 'end', 'created_at', 'updated_at', 'deleted_at');

	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/
	
	public function service()
	{
		return $this->belongsTo('ServiceModel', 'service_id');
	}

	public function staffAppointments()
	{
		return $this->hasMany('StaffAppointmentModel', 'occurrence_id');
	}

	public function userAppointments()
	{
		return $this->hasMany('UserAppointmentModel', 'occurrence_id');
	}
	
}