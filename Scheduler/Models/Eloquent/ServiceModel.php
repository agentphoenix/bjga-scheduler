<?php namespace Scheduler\Models\Eloquent;

use Str, Model;

class ServiceModel extends Model {

	protected $table = 'services';

	protected $fillable = array(
		'category', 'staff_id', 'name', 'slug', 'description', 'price', 
		'occurrences', 'duration', 'user_limit',
	);

	protected $softDelete = true;

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
	| Model Accessors
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

	public function isLesson()
	{
		return (bool) ($this->serviceOccurrences->count() == 0 and $this->user_limit == 1);
	}

	public function isProgram()
	{
		return (bool) ($this->user_limit > 1);
	}
	
}