<?php namespace Scheduler\Models\Eloquent;

use Model;

class UserAppointmentModel extends Model {

	protected $table = 'users_appointments';

	protected $fillable = array(
		'appointment_id', 'recur_id', 'user_id', 'has_gift', 'gift_amount', 'paid', 'amount',
	);

	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/
	
	public function appointment()
	{
		return $this->belongsTo('StaffAppointmentModel');
	}

	public function user()
	{
		return $this->belongsTo('UserModel');
	}

	public function recur()
	{
		return $this->belongsTo('StaffAppointmentRecurModel');
	}

	/*
	|--------------------------------------------------------------------------
	| Scopes
	|--------------------------------------------------------------------------
	*/

	public function scopeDate($query, $date)
	{
		$query->join('staff_appointments', 'users_appointments.appointment_id', '=', 'staff_appointments.id')
			->where('start', '>=', $date->toDateTimeString());
	}

	public function scopeAttendee($query, $user)
	{
		$query->where('user_id', $user);
	}
	
}