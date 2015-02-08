<?php namespace Plans\Data;

use Model;
use Laracasts\Presenter\PresentableTrait;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Goal extends Model {

	use PresentableTrait;
	use SoftDeletingTrait;

	protected $table = 'development_plans_goals';

	protected $fillable = ['plan_id', 'title', 'summary', 'completed',
		'completed_date'];

	protected $dates = ['created_at', 'updated_at', 'deleted_at', 'completed_date'];

	protected $presenter = 'Plans\Data\Presenters\GoalPresenter';

	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/

	public function plan()
	{
		return $this->belongsTo('Plan');
	}

	public function conversations()
	{
		return $this->hasMany('Conversation');
	}
	
}
