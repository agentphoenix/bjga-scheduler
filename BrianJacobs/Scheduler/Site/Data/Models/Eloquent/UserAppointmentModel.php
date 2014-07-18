<?php namespace Scheduler\Data\Models\Eloquent;

use Model;
use Laracasts\Presenter\PresentableTrait;

class UserAppointmentModel extends Model {

	use PresentableTrait;

	protected $table = 'users_appointments';

	protected $fillable = array(
		'appointment_id', 'recur_id', 'occurrence_id', 'user_id', 'paid', 'amount', 'received',
	);

	protected $softDelete = true;

	protected $dates = array('created_at', 'updated_at', 'deleted_at');

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
	
}