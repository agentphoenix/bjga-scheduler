<?php namespace Scheduler\Data\Models\Eloquent;

use Hash,
	Model;
use Illuminate\Auth\UserInterface,
	Illuminate\Auth\Reminders\RemindableInterface;
use Laracasts\Presenter\PresentableTrait;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class UserModel extends Model implements UserInterface, RemindableInterface {

	use PresentableTrait;
	use SoftDeletingTrait;

	protected $table = 'users';

	protected $fillable = array('name', 'email', 'password', 'phone', 'address',
		'remember_token');

	protected $hidden = array('password', 'remember_token');

	protected $hashableAttributes = array('password');

	protected $dates = array('created_at', 'updated_at', 'deleted_at');

	protected $presenter = 'Scheduler\Data\Presenters\UserPresenter';

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

	public function credits()
	{
		return $this->hasMany('CreditModel', 'user_id');
	}

	public function plan()
	{
		return $this->hasOne('Plan', 'user_id');
	}

	public function conversations()
	{
		return $this->hasMany('Conversation', 'user_id');
	}

	public function stats()
	{
		return $this->hasMany('Stat', 'user_id');
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
	public function isAttending($serviceId)
	{
		// Get the service
		$service = ServiceModel::find($serviceId);

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

	public function getCredits($instructor = false)
	{
		// Get all the credits
		$credits = $this->credits;

		// Start an array to track credits
		$finalCredits['time'] = 0;
		$finalCredits['money'] = 0;

		// Get all the time credits
		$time = $credits->filter(function($t) use ($instructor)
		{
			if ($instructor) return $t->type == 'time' and $t->staff_id == $instructor;

			return $t->type == 'time';
		});

		if ($time->count() > 0)
		{
			// Start off with zero time
			$finalTime = 0;

			// Iterate through the collection
			$time->each(function($t) use (&$finalTime)
			{
				$finalTime += (float) $t->value - (float) $t->claimed;
			});

			$finalCredits['time'] = $finalTime;
		}

		// Get all the money credits
		$money = $credits->filter(function($m) use ($instructor)
		{
			if ($instructor) return $m->type == 'money' and $m->staff_id == $instructor;

			return $m->type == 'money';
		});

		if ($money->count() > 0)
		{
			// Start off with zero money
			$finalMoney = 0;

			// Iterate through the collection
			$money->each(function($m) use (&$finalMoney)
			{
				$finalMoney += (float) $m->value - (float) $m->claimed;
			});

			$finalCredits['money'] = $finalMoney;
		}

		return $finalCredits;
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