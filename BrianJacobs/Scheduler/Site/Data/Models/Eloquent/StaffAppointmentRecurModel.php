<?php namespace Scheduler\Data\Models\Eloquent;

use Date, Model;
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

	public function staff()
	{
		return $this->belongsTo('StaffModel', 'staff_id');
	}

	/*
	|--------------------------------------------------------------------------
	| Model Methods
	|--------------------------------------------------------------------------
	*/

	public function hasEnded()
	{
		if ($this->staffAppointments->count() > 0)
		{
			$last = $this->staffAppointments->sortBy('start')->last();
			
			if ($last->end->lt(Date::now()->endOfDay())) return true;
		}

		return false;
	}

	public function hasStarted()
	{
		if ($this->staffAppointments->count() > 0)
		{
			$first = $this->staffAppointments->sortBy('start')->first();
			
			if ($first->start->gte(Date::now()->startOfDay())) return true;
		}

		return false;
	}

}