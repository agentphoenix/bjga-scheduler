<?php namespace Plans\Data;

use Model;
use Laracasts\Presenter\PresentableTrait;

class GoalCompletion extends Model {

	use PresentableTrait;

	public $timestamps = false;

	protected $table = 'plans_goals_completion';

	protected $fillable = ['goal_id', 'target_date', 'type', 'metric',
		'operator', 'value', 'count'];

	protected $dates = ['target_date'];

	protected $presenter = 'Plans\Data\Presenters\GoalCompletionPresenter';

	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/

	public function goal()
	{
		return $this->belongsTo('Goal');
	}
	
}
