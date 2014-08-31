<?php namespace Scheduler\Data\Models\Eloquent;

use Model;
use Laracasts\Presenter\PresentableTrait;

class StaffAppointmentRecurModel extends Model {

	use PresentableTrait;

	protected $table = 'staff_appointments_recurring';

	public $timestamps = false;

	protected $fillable = array('staff_id', 'service_id', 'start', 'end');

	protected $dates = array('start', 'end');

	protected $presenter = 'Scheduler\Data\Presenters\StaffAppointmentRecurPresenter';

	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/
	
	public function staffAppointments()
	{
		return $this->hasMany('StaffAppointmentModel', 'recur_id');
	}

	public function userAppointments()
	{
		return $this->hasMany('UserAppointmentModel', 'recur_id');
	}

}