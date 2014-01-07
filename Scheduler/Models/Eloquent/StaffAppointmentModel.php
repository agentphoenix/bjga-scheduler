<?php namespace Scheduler\Models\Eloquent;

use Model;

class StaffAppointmentModel extends Model {

	protected $table = 'staff_appointments';

	protected $fillable = array(
		'staff_id', 'service_id', 'recur_id', 'start', 'end', 'notes',
	);

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
	
	public function staff()
	{
		return $this->belongsTo('StaffModel');
	}
	
	public function attendees()
	{
		return $this->hasMany('UserAppointmentModel', 'appointment_id');
	}

	public function recur()
	{
		return $this->belongsTo('StaffAppointmentRecurModel', 'recur_id');
	}

	/*
	|--------------------------------------------------------------------------
	| Getters/Setters
	|--------------------------------------------------------------------------
	*/

	public function getDates()
	{
		return array('start', 'end', 'created_at', 'updated_at', 'deleted_at');
	}
	
}