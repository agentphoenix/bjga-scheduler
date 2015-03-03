<?php namespace Scheduler\Data\Models\Eloquent;

use Date, Model;
use Laracasts\Presenter\PresentableTrait;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class StaffAppointmentModel extends Model {

	use PresentableTrait;
	use SoftDeletingTrait;

	protected $table = 'staff_appointments';

	protected $fillable = array(
		'staff_id', 'service_id', 'recur_id', 'occurrence_id', 'start', 'end', 
		'notes', 'location_id',
	);

	protected $dates = array('start', 'end', 'created_at', 'updated_at', 'deleted_at');

	protected $presenter = 'Scheduler\Data\Presenters\StaffAppointmentPresenter';

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

	public function location()
	{
		return $this->belongsTo('LocationModel', 'location_id');
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

	public function hasEnded()
	{
		// Get right now
		$now = Date::now();

		if ($now > $this->end)
		{
			return true;
		}

		return false;
	}

	public function hasStarted()
	{
		// Get right now
		$now = Date::now();

		if ($this->service->isRecurring())
		{
			if ($this->service->isLesson())
			{
				// Get the first appointment
				$firstAppt = $this->recur->staffAppointments->sortBy(function($s)
				{
					return $s->start;
				})->first();
			}

			if ($this->service->isProgram())
			{
				// Get the first appointment
				$firstAppt = $this->occurrence->staffAppointments->sortBy(function($s)
				{
					return $s->start;
				})->first();
			}

			if ($now > $firstAppt->start)
			{
				return true;
			}
		}
		else
		{
			if ($now > $this->start)
			{
				return true;
			}
		}

		return false;
	}

	public function userAppointment($user)
	{
		return $this->userAppointments->filter(function($u) use ($user)
		{
			return $u->user_id == $user->id;
		})->first();
	}
	
}