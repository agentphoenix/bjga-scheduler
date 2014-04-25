<?php namespace Scheduler\Models\Eloquent;

use Model;

class BookingMetaModel extends Model {

	protected $table = 'booking_meta';

	protected $fillable = array(
		'user_id', 'user_name', 'staff_appointment_ids', 'user_appointment_ids',
		'os', 'browser',
	);

	protected $dates = array('created_at', 'updated_at');

}