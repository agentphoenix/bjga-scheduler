<?php namespace Scheduler\Models;

use Model;

class Staff extends Model {

	protected $table = 'staff';

	protected $fillable = array(
		'user_id', 'title', 'bio', 'access',
	);

	protected $hidden = array(
		'access',
	);

	protected $dates = array(
		'created_at', 'updated_at',
	);
	
	protected static $properties = array(
		'id', 'user_id', 'title', 'bio', 'access', 'created_at', 'updated_at',
	);

	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/
	
	/**
	 * Belongs To: User
	 */
	public function user()
	{
		return $this->belongsTo('User');
	}

	/**
	 * Has Many: Services
	 */
	public function services()
	{
		return $this->hasMany('Service');
	}
	
	/**
	 * Has Many: Appointment
	 */
	public function appointments()
	{
		return $this->hasMany('Appointment');
	}

	/**
	 * Has Many: Schedule
	 */
	public function schedule()
	{
		return $this->hasMany('Schedule');
	}

	/**
	 * Has Many: Exceptions
	 */
	public function exceptions()
	{
		return $this->hasMany('ScheduleException');
	}
	
}