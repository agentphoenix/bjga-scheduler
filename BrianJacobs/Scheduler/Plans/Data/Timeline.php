<?php namespace Plans\Data;

use Model;
use Laracasts\Presenter\PresentableTrait;

class Timeline extends Model {

	use PresentableTrait;

	protected $table = 'development_plans_timeline';

	protected $fillable = ['plan_id', 'goal_id', 'user_id', 'type', 'type_id'];

	protected $dates = ['created_at', 'updated_at'];

	protected $presenter = 'Plans\Data\Presenters\TimelinePresenter';

	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/

	public function plan()
	{
		return $this->belongsTo('Plan');
	}
	
}
