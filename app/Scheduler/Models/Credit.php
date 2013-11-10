<?php namespace Scheduler\Models;

use Model;

class Credit extends Model {

	protected $table = 'users_credits';

	protected $fillable = array(
		'user_id', 'type', 'amount',
	);

	protected $dates = array(
		'created_at', 'updated_at',
	);
	
	protected static $properties = array(
		'id', 'user_id', 'type', 'amount', 'created_at', 'updated_at',
	);

	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/
	
	/**
	 * Belongs To: User
	 */
	public function user()
	{
		return $this->belongsTo('User');
	}
	
	/*
	|--------------------------------------------------------------------------
	| Model Accessors
	|--------------------------------------------------------------------------
	*/

	public function setAmountAttribute($value)
	{
		// Convert the amount from hours to minutes (if type is credits)
		if ($this->attributes['type'] == 'hours')
			$this->attributes['amount'] = $value * 60;
	}
	
	public function getAmountAttribute($value)
	{
		// Convert the duration from minutes to hours
		if ($this->attributes['type'] == 'hours' and $value > 0)
			$this->attributes['amount'] = $value / 60;
	}
	
}