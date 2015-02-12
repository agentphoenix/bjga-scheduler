<?php namespace Scheduler\Data\Models\Eloquent;

use Model;
use Laracasts\Presenter\PresentableTrait;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class CreditModel extends Model {

	use PresentableTrait;
	use SoftDeletingTrait;

	protected $table = 'users_credits';

	protected $fillable = ['code', 'type', 'value', 'claimed', 'user_id', 'email',
		'expires', 'notes', 'staff_id'];

	protected $dates = ['created_at', 'updated_at', 'deleted_at', 'expires'];

	protected $presenter = 'Scheduler\Data\Presenters\CreditPresenter';

	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/

	public function user()
	{
		return $this->belongsTo('UserModel', 'user_id');
	}

	/*
	|--------------------------------------------------------------------------
	| Getters/Setters
	|--------------------------------------------------------------------------
	*/

	public function setClaimedAttribute($value)
	{
		if ($this->attributes['type'] == 'time')
		{
			$this->attributes['claimed'] = $value * 60;
		}
		else
		{
			$this->attributes['claimed'] = $value;
		}
	}

	public function setValueAttribute($value)
	{
		if ($this->attributes['type'] == 'time')
		{
			$this->attributes['value'] = $value * 60;
		}
		else
		{
			$this->attributes['value'] = $value;
		}
	}
	
}