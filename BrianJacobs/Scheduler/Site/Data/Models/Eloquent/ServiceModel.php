<?php namespace Scheduler\Data\Models\Eloquent;

use Str, Model;
use Laracasts\Presenter\PresentableTrait;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class ServiceModel extends Model {

	use PresentableTrait;
	use SoftDeletingTrait;

	protected $table = 'services';

	protected $fillable = array(
		'category', 'staff_id', 'name', 'slug', 'description', 'price', 
		'occurrences', 'duration', 'user_limit', 'order', 'status', 'loyalty',
		'occurrences_schedule', 'location_id', 'summary',
	);

	protected $dates = array('created_at', 'updated_at', 'deleted_at');

	protected $presenter = 'Scheduler\Data\Presenters\ServicePresenter';

	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/

	public function staff()
	{
		return $this->belongsTo('StaffModel');
	}
	
	public function appointments()
	{
		return $this->hasMany('StaffAppointmentModel', 'service_id');
	}

	public function serviceOccurrences()
	{
		return $this->hasMany('ServiceOccurrenceModel', 'service_id');
	}

	public function location()
	{
		return $this->belongsTo('LocationModel', 'location_id');
	}
	
	/*
	|--------------------------------------------------------------------------
	| Getters/Setters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Make sure the slug is stored properly.
	 *
	 * @param	string	$value	Slug
	 * @return	void
	 */
	public function setSlugAttribute($value)
	{
		if (empty($value))
		{
			// Get the staff argument
			$staff = StaffModel::find($this->attributes['staff_id']);

			// Get the staff member's last name
			$name = explode(' ', $staff->user->name);

			// Get the last name
			$lastname = strtolower(end($name));

			$this->attributes['slug'] = Str::slug($this->attributes['name']." ".$lastname);
		}
		else
		{
			$this->attributes['slug'] = $value;
		}
	}

	/*
	|--------------------------------------------------------------------------
	| Model Scopes
	|--------------------------------------------------------------------------
	*/

	/**
	 * Scope the query to a specific category.
	 *
	 * @param	Builder		$query		Query object
	 * @param	string		$category
	 * @return	void
	 */
	public function scopeGetCategory($query, $category)
	{
		$query->where('category', $category);
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

		if ($this->appointments->count() > 0)
		{
			foreach ($this->appointments as $a)
			{
				if ($a->userAppointments->count() > 0)
				{
					foreach ($a->userAppointments as $u)
					{
						if ( ! $collection->has($u->user->id))
							$collection->put($u->user->id, $u->user);
					}
				}
			}
		}

		return $collection;
	}

	public function getFirstAppointment()
	{
		return $this->appointments->sortBy('start')->first();
	}

	public function isLesson()
	{
		return (bool) ($this->serviceOccurrences->count() == 0 and $this->user_limit == 1);
	}

	public function isProgram()
	{
		return (bool) ($this->user_limit > 1);
	}

	public function isRecurring()
	{
		return (bool) ($this->occurrences > 1);
	}
	
}