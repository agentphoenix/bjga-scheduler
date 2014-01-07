<?php namespace Scheduler\Models\Eloquent;

use Model;

class StaffAppointmentRecurModel extends Model {

	protected $table = 'staff_appointments_recurring';

	public $timestamps = false;

	protected $fillable = array('staff_id', 'service_id', 'start', 'end');

	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/
	
	public function staffAppointments()
	{
		return $this->hasMany('StaffAppointmentModel');
	}

	public function userAppointments()
	{
		return $this->hasMany('UserAppointmentModel');
	}

	/*
	|--------------------------------------------------------------------------
	| Getters/Setters
	|--------------------------------------------------------------------------
	*/

	public function getDates()
	{
		return array('start', 'end');
	}

}