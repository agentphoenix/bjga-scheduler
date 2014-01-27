<?php namespace Scheduler\Models\Eloquent;

use Model;

class StaffAppointmentModel extends Model {

	protected $table = 'staff_appointments';

	protected $fillable = array(
		'staff_id', 'service_id', 'recur_id', 'occurrence_id', 'start', 'end', 
		'notes',
	);

	protected $softDelete = true;

	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/
	
	public function service()
	{
		return $this->belongsTo('ServiceModel', 'service_id');
	}
	
	public function staff()
	{
		return $this->belongsTo('StaffModel', 'staff_id');
	}

	public function userAppointments()
	{
		return $this->hasMany('UserAppointmentModel', 'appointment_id');
	}

	public function recur()
	{
		return $this->belongsTo('StaffAppointmentRecurModel', 'recur_id');
	}

	public function occurrence()
	{
		return $this->belongsTo('ServiceOccurrenceModel', 'occurrence_id');
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

	/*
	|--------------------------------------------------------------------------
	| Model Methods
	|--------------------------------------------------------------------------
	*/

	public function attendees()
	{
		// Start a new collection
		$collection = $this->newCollection();
		
		if ($this->userAppointments->count() > 0)
		{
			foreach ($this->userAppointments as $a)
			{
				if ( ! $collection->has($a->user->id))
					$collection->put($a->user->id, $a->user);
			}
		}

		return $collection;
	}
	
}