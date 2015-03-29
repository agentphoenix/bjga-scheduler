<?php namespace Scheduler\Data\Models\Eloquent;

use Model;
use Laracasts\Presenter\PresentableTrait;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class StaffModel extends Model {

	use PresentableTrait;
	use SoftDeletingTrait;

	protected $table = 'staff';

	protected $fillable = array('user_id', 'title', 'bio', 'access', 'instruction');

	protected $dates = array('created_at', 'updated_at', 'deleted_at');

	protected $presenter = 'Scheduler\Data\Presenters\StaffPresenter';

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

	public function credits()
	{
		return $this->hasMany('CreditModel', 'staff_id');
	}

	public function recurringAppointments()
	{
		return $this->hasMany('StaffAppointmentRecurModel', 'staff_id');
	}

	/*
	|--------------------------------------------------------------------------
	| Model Methods
	|--------------------------------------------------------------------------
	*/

	public function getScheduleForDay($day)
	{
		return $this->schedule->filter(function($s) use ($day)
		{
			return $s->day == $day;
		})->first();
	}
	
}
