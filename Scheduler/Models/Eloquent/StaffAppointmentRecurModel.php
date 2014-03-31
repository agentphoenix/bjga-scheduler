<?php namespace Scheduler\Models\Eloquent;

use Model;
use Scheduler\Presenter\PresentableTrait;
//use Laracasts\Presenter\PresentableTrait;

class StaffAppointmentRecurModel extends Model {

	use PresentableTrait;

	protected $table = 'staff_appointments_recurring';

	public $timestamps = false;

	protected $fillable = array('staff_id', 'service_id', 'start', 'end');

	protected $presenter = 'Scheduler\Presenter\Presenter\StaffAppointmentRecurPresenter';

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