<?php namespace Scheduler\Data\Models\Eloquent;

use Model;

class StaffScheduleModel extends Model {

	protected $table = 'staff_schedules';

	public $timestamps = false;

	protected $fillable = array('staff_id', 'day', 'availability', 'location_id');

	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/
	
	public function staff()
	{
		return $this->belongsTo('StaffModel');
	}

	public function location()
	{
		return $this->belongsTo('LocationModel', 'location_id', 'id');
	}
	
}