<?php namespace Plans\Data;

use Model;
use Laracasts\Presenter\PresentableTrait;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Goal extends Model {

	use PresentableTrait;
	use SoftDeletingTrait;

	protected $table = 'plans_goals';

	protected $fillable = ['plan_id', 'title', 'summary', 'completed',
		'completed_date', 'target_date', 'target_type', 'target_metric',
		'target_operator', 'target_value'];

	protected $dates = ['created_at', 'updated_at', 'deleted_at',
		'completed_date', 'target_date'];

	protected $touches = ['plan'];

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

	public function comments()
	{
		return $this->hasMany('Comment');
	}

	public function completion()
	{
		return $this->hasOne('GoalCompletion');
	}

	public function lessons()
	{
		return $this->hasMany('StaffAppointmentModel', 'plan_goal_id');
	}

	public function stats()
	{
		return $this->hasMany('Stat');
	}

	/*
	|--------------------------------------------------------------------------
	| Model Methods
	|--------------------------------------------------------------------------
	*/

	public function countStats()
	{
		return (int) $this->stats->filter(function($s)
		{
			return $s->type != 'tournament' and $s->type != 'message';
		})->count();
	}

	public function countTournaments()
	{
		return (int) $this->stats->filter(function($s)
		{
			return $s->type == 'tournament';
		})->count();
	}

	public function isComplete()
	{
		return (bool) ($this->completed == 1);
	}
	
}
