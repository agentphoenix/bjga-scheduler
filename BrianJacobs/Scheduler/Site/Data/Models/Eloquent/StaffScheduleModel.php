<?php namespace Scheduler\Data\Models\Eloquent;

use Model;

class StaffScheduleModel extends Model {

	protected $table = 'staff_schedules';

	public $timestamps = false;

	protected $fillable = array('staff_id', 'day', 'availability');

	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/
	
	public function staff()
	{
		return $this->belongsTo('StaffModel');
	}
	
}