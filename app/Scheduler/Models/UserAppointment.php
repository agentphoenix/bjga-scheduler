<?php namespace Scheduler\Models;

use Model;

class UserAppointment extends Model {

	public $timestamps = false;

	protected $table = 'users_appointments';

	protected $fillable = array(
		'appointment_id', 'user_id', 'gift_certificate', 'gift_certificate_amount',
		'payment_type', 'paid', 'amount',
	);
	
	protected static $properties = array(
		'id', 'appointment_id', 'user_id', 'gift_certificate', 'gift_certificate_amount',
		'payment_type', 'paid', 'amount',
	);

	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/
	
	/**
	 * Belongs To: Appointment
	 */
	public function appointment()
	{
		return $this->belongsTo('Appointment')
			->orderBy('staff_appointments.date', 'asc');
	}

	/**
	 * Belongs To: User
	 */
	public function user()
	{
		return $this->belongsTo('User');
	}

	/*
	|--------------------------------------------------------------------------
	| Model Methods
	|--------------------------------------------------------------------------
	*/

	public function withdraw()
	{
		// Get the service
		$service = $this->appointment->service;

		if ($service->isOneToOne())
		{
			// Remove the staff appointment
			$this->appointment->delete();

			// Remove the user appointment
			$this->delete();
		}

		if ($service->isOneToMany())
		{
			// Remove the user appointment
			$this->delete();
		}

		if ($service->isManyToMany())
		{
			// Start a query
			$query = static::startQuery();

			// Get all the appointments
			$appointments = $query->where('appointment_id', $this->appointment->id)
				->where('user_id', $this->user_id)
				->get();

			foreach ($appointments as $appt)
			{
				$appt->delete();
			}
		}

		# TODO: Send an email

		# TODO: Figure out if we need to refund credits
	}
	
}