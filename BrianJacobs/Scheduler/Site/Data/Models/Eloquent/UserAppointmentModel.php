<?php namespace Scheduler\Data\Models\Eloquent;

use Date,
	Model;
use Laracasts\Presenter\PresentableTrait;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class UserAppointmentModel extends Model {

	use PresentableTrait;
	use SoftDeletingTrait;

	protected $table = 'users_appointments';

	protected $fillable = ['appointment_id', 'recur_id', 'occurrence_id',
		'user_id', 'paid', 'amount', 'received'];

	protected $dates = ['created_at', 'updated_at', 'deleted_at'];

	protected $presenter = 'Scheduler\Data\Presenters\UserAppointmentPresenter';

	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/
	
	public function appointment()
	{
		return $this->belongsTo('StaffAppointmentModel', 'appointment_id');
	}

	public function user()
	{
		return $this->belongsTo('UserModel', 'user_id');
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
	| Scopes
	|--------------------------------------------------------------------------
	*/

	public function scopeDate($query, $date, $equality = '>=')
	{
		$query->join('staff_appointments', 'users_appointments.appointment_id', '=', 'staff_appointments.id')
			->where('start', $equality, $date);
	}

	public function scopeAttendee($query, $user)
	{
		$query->where('user_id', $user);
	}

	/*
	|--------------------------------------------------------------------------
	| Model Methods
	|--------------------------------------------------------------------------
	*/

	public function due()
	{
		return (float) $this->amount - (float) $this->received;
	}

	public function getAllUserAppointments()
	{
		return $this->appointment->userAppointments;
	}

	public function getStaffAppointment()
	{
		return $this->appointment;
	}

	public function getUserAppointment()
	{
		return $this;
	}

	public function hasEnded()
	{
		return (bool) Date::now() > $this->appointment->end;
	}

	public function hasStarted()
	{
		return (bool) Date::now() >= $this->appointment->start;
	}

	public function isPaid()
	{
		return (bool) $this->paid;
	}
	
}