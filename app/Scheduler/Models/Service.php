<?php namespace Scheduler\Models;

use Str;
use Model;

class Service extends Model {

	protected $table = 'services';

	protected $fillable = array(
		'category_id', 'staff_id', 'name', 'slug', 'description', 'price', 
		'occurrences', 'duration', 'additional_services', 'user_limit',
	);

	protected $dates = array(
		'created_at', 'updated_at',
	);
	
	protected static $properties = array(
		'id', 'category_id', 'staff_id', 'name', 'slug', 'description', 'price', 
		'occurrences', 'duration', 'additional_services', 'user_limit',
		'created_at', 'updated_at',
	);

	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/
	
	/**
	 * Belongs To: Category
	 */
	public function category()
	{
		return $this->belongsTo('Category');
	}

	/**
	 * Belongs To: Staff
	 */
	public function staff()
	{
		return $this->belongsTo('Staff');
	}
	
	/**
	 * Has Many: Appointments
	 */
	public function appointments()
	{
		return $this->hasMany('Appointment');
	}

	/**
	 * Has Many: Service Occurrences
	 */
	public function serviceOccurrences()
	{
		return $this->hasMany('ServiceOccurrence');
	}
	
	/*
	|--------------------------------------------------------------------------
	| Model Accessors
	|--------------------------------------------------------------------------
	*/

	/**
	 * Make sure the duration is stored in minutes.
	 *
	 * @param	string	$value	Duration
	 * @return	void
	 */
	public function setDurationAttribute($value)
	{
		$this->attributes['duration'] = $value * 60;
	}

	/**
	 * Make sure the price is stored properly.
	 *
	 * @param	string	$value	Price
	 * @return	void
	 */
	public function setPriceAttribute($value)
	{
		$this->attributes['price'] = ( ! empty($value)) ? $value : "Free";
	}

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
	
	/**
	 * Make sure the duration is pulled in hours.
	 *
	 * @param	string	$value	Slug
	 * @return	string
	 */
	public function getDurationAttribute($value)
	{
		if ($value > 0) return $value / 60;
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
	 * @param	int			$categoryID
	 * @return	void
	 */
	public function scopeGetCategory($query, $categoryID)
	{
		$query->where('category_id', $categoryID);
	}

	/*
	|--------------------------------------------------------------------------
	| Model Methods
	|--------------------------------------------------------------------------
	*/

	/**
	 * Is this a 1 occurrence/1 user service?
	 *
	 * @return	bool
	 */
	public function isOneToOne()
	{
		return (bool) ($this->serviceOccurrences->count() == 0 and $this->user_limit == 1);
	}

	/**
	 * Is this a 1 occurrence/many users service?
	 *
	 * @return	bool
	 */
	public function isOneToMany()
	{
		return (bool) ($this->serviceOccurrences->count() == 1 and $this->user_limit > 1);
	}

	/**
	 * Is this a many occurrence/many users service?
	 *
	 * @return	bool
	 */
	public function isManyToMany()
	{
		return (bool) ($this->serviceOccurrences->count() > 1 and $this->user_limit > 1);
	}
	
}