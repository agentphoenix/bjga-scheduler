<?php namespace Scheduler\Models\Eloquent;

use Hash,
	Model;
use Illuminate\Auth\UserInterface,
	Illuminate\Auth\Reminders\RemindableInterface;

class UserModel extends Model implements UserInterface, RemindableInterface {

	protected $table = 'users';

	protected $softDelete = true;

	protected $fillable = array('name', 'email', 'password', 'phone', 'address',
		'remember_token');

	protected $hidden = array('password', 'remember_token');

	protected $hashableAttributes = array('password');

	protected $dates = array('created_at', 'updated_at', 'deleted_at');

	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/
	
	public function staff()
	{
		return $this->hasOne('StaffModel', 'user_id');
	}

	public function appointments()
	{
		return $this->hasMany('UserAppointmentModel', 'user_id');
	}

	public function credit()
	{
		return $this->hasMany('CreditModel', 'user_id');
	}

	/*
	|--------------------------------------------------------------------------
	| Getters/Setters
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

	/**
	 * Make sure the phone number is in the proper format.
	 *
	 * @param	string	$value	Password
	 * @return	void
	 */
	public function setPhoneAttribute($value)
	{
		$this->attributes['phone'] = preg_replace('~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~', '$1-$2-$3', $value);
	}

	/*
	|--------------------------------------------------------------------------
	| Model Methods
	|--------------------------------------------------------------------------
	*/

	/**
	 * Is the user attending a service?
	 *
	 * @param	int		$id		Service ID
	 * @return	bool
	 */
	public function isAttending($id)
	{
		// Get the service
		$service = ServiceModel::find($id);

		if ($service)
		{
			// Get the current object
			$user = $this;

			// Filter the attendees
			$attendees = $service->attendees()->filter(function($a) use ($user)
			{
				return (int) $a->id === (int) $user->id;
			});

			return (bool) ($attendees->count() > 0);
		}

		return false;
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
		return $this->appointments->filter(function($a) use ($id)
		{
			return $a->appointment_id == $id;
		});
	}

	public function access()
	{
		if ($this->isStaff())
		{
			return (int) $this->staff->access;
		}

		return false;
	}

	public function credits()
	{
		// Get all the credits
		$credits = $this->credit;

		// Start a collection to track credits
		$finalCredits = $this->newCollection();

		// Get all the time credits
		$time = $credits->filter(function($t)
		{
			return $t->type == 'time';
		});

		if ($time->count() > 0)
		{
			$finalTime = 0;

			$finalTime = $time->each(function($t) use ($finalTime)
			{
				return $finalTime += (int) $t->value;
			});

			$finalCredits->put('time', $finalTime);
		}

		// Get all the money credits
		$money = $credits->filter(function($m)
		{
			return $t->type == 'money';
		});

		if ($money->count() > 0)
		{
			//
		}
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

	public function getRememberToken()
	{
		return $this->remember_token;
	}

	public function setRememberToken($value)
	{
		$this->remember_token = $value;
	}

	public function getRememberTokenName()
	{
		return 'remember_token';
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