<?php namespace Plans\Data;

use Model;
use Laracasts\Presenter\PresentableTrait;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Plan extends Model {

	use PresentableTrait, SoftDeletingTrait;

	protected $table = 'plans';

	protected $fillable = ['user_id'];

	protected $dates = ['created_at', 'updated_at', 'deleted_at'];

	protected $presenter = 'Plans\Data\Presenters\PlanPresenter';

	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/

	public function goals()
	{
		return $this->hasMany('Goal');
	}

	public function activeGoals()
	{
		return $this->hasMany('Goal')->where('completed', '!=', 1);
	}

	public function instructors()
	{
		return $this->belongsToMany('StaffModel', 'plans_instructors', 'plan_id', 'staff_id');
	}

	public function user()
	{
		return $this->belongsTo('UserModel', 'user_id');
	}
	
}
