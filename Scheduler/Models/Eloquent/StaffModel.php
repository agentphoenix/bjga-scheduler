<?php namespace Scheduler\Models\Eloquent;

use Model;

class StaffModel extends Model {

	protected $table = 'staff';

	protected $fillable = array('user_id', 'title', 'bio', 'access', 'instruction');

	protected $softDelete = true;

	protected $dates = array('created_at', 'updated_at', 'deleted_at');

	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/
	
	public function user()
	{
		return $this->belongsTo('UserModel');
	}

	public function services()
	{
		return $this->hasMany('ServiceModel', 'staff_id');
	}
	
	public function appointments()
	{
		return $this->hasMany('StaffAppointmentModel', 'staff_id');
	}

	public function schedule()
	{
		return $this->hasMany('StaffScheduleModel', 'staff_id');
	}
	
}