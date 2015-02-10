<?php namespace Plans\Data;

use Model;
use Laracasts\Presenter\PresentableTrait;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Plan extends Model {

	use PresentableTrait;
	use SoftDeletingTrait;

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

	public function instructors()
	{
		return $this->belongsToMany('StaffModel', 'development_plans_instructors', 'staff_id', 'plan_id');
	}

	public function user()
	{
		return $this->belongsTo('UserModel', 'user_id');
	}

	public function conversations()
	{
		return $this->hasMany('Conversation')->orderBy('created_at', 'desc');
	}
	
}
