<?php namespace Scheduler\Models;

use Hash;
use Model;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Model implements UserInterface, RemindableInterface {

	protected $table = 'users';

	protected $fillable = array(
		'name', 'email', 'password', 'phone', 'address',
	);

	protected $hidden = array('password');

	protected $hashableAttributes = array('password');

	protected $dates = array('created_at', 'updated_at');
	
	protected static $properties = array(
		'id', 'name', 'email', 'password', 'phone', 'address', 'created_at', 
		'updated_at',
	);

	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/
	
	/**
	 * Has One: Staff
	 */
	public function staff()
	{
		return $this->hasOne('Staff');
	}
	
	/**
	 * Has One: Credit
	 */
	public function credit()
	{
		return $this->hasOne('Credit');
	}

	/**
	 * Has Many: Appointment
	 */
	public function appointments()
	{
		return $this->hasMany('UserAppointment');
	}

	/*
	|--------------------------------------------------------------------------
	| Model Accessors
	|--------------------------------------------------------------------------
	*/

	/**
	 * Make sure the password is hashed.
	 *
	 * @param	string	$value	Password
	 * @return	void
	 */
	public function setPasswordAttribute($value)
	{
		$this->attributes['password'] = Hash::make($value);
	}

	/*
	|--------------------------------------------------------------------------
	| Model Methods
	|--------------------------------------------------------------------------
	*/

	/**
	 * Is the user attending an appointment?
	 *
	 * @param	int		$id		Appointment ID
	 * @return	bool
	 */
	public function isAttending($id)
	{
		$appointment = $this->getAppointment($id);

		return (bool) ($appointment->count() > 0);
	}

	/**
	 * Is the user a staff member?
	 *
	 * @return	bool
	 */
	public function isStaff()
	{
		return ($this->staff !== null);
	}

	public function getAppointment($id)
	{
		return $this->appointments->filter(function($a) use($id)
		{
			return $a->appointment_id == $id;
		});
	}

	public function access()
	{
		if ($this->isStaff())
			return (int) $this->staff->access;

		return false;
	}

	/*
	|--------------------------------------------------------------------------
	| User Interface Implementation
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return	mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return	string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/*
	|--------------------------------------------------------------------------
	| Remindable Interface Implementation
	|--------------------------------------------------------------------------
	*/

	public function getReminderEmail()
	{
		return $this->email;
	}
	
}