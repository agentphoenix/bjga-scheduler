<?php namespace Scheduler\Data\Models\Eloquent;

use Str, Model;
use Laracasts\Presenter\PresentableTrait;

class ServiceModel extends Model {

	use PresentableTrait;

	protected $table = 'services';

	protected $fillable = array(
		'category', 'staff_id', 'name', 'slug', 'description', 'price', 
		'occurrences', 'duration', 'user_limit', 'order', 'status', 'loyalty',
		'occurrences_schedule',
	);

	protected $softDelete = true;

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
		$this->attributes['slug'] = (empty($value)) 
			? Str::slug($this->attributes['name'])
			: $value;
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