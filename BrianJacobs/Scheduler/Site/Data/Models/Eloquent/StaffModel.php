<?php namespace Scheduler\Data\Models\Eloquent;

use Model;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class StaffModel extends Model {

	use SoftDeletingTrait;

	protected $table = 'staff';

	protected $fillable = array('user_id', 'title', 'bio', 'access', 'instruction');

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