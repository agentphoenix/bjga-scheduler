<?php namespace Scheduler\Models;

use Auth;
use Event;
use Model;
use Config;
use Calendar;

class Appointment extends Model {

	protected $table = 'staff_appointments';

	protected $fillable = array(
		'staff_id', 'service_id', 'date', 'start_time', 'end_time', 'notes',
	);

	protected $dates = array(
		'created_at', 'updated_at',
	);
	
	protected static $properties = array(
		'id', 'user_id', 'service_id', 'date', 'start_time', 'end_time', 'notes',
		'created_at', 'updated_at',
	);

	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/
	
	/**
	 * Belongs To: Service
	 */
	public function service()
	{
		return $this->belongsTo('Service');
	}
	
	/**
	 * Belongs To: User
	 */
	public function staff()
	{
		return $this->belongsTo('Staff');
	}
	
	/**
	 * Belongs To Many: Users (through Users Events)
	 */
	public function attendees()
	{
		return $this->hasMany('UserAppointment');
	}

	/*
	|--------------------------------------------------------------------------
	| Model Methods
	|--------------------------------------------------------------------------
	*/

	public function enroll()
	{
		// Get the user
		$user = Auth::user();

		if ($this->service->isOneToOne())
		{
			//
		}

		if ($this->service->isOneToMany())
		{
			UserAppointment::create(array(
				'appointment_id'	=> $this->id,
				'user_id'			=> $user->id,
			));
		}

		if ($this->service->isManyToMany())
		{
			//
		}

		# TODO: send an email
	}
	
}