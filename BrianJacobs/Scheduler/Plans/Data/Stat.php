<?php namespace Plans\Data;

use Model;
use Laracasts\Presenter\PresentableTrait;

class Stat extends Model {

	use PresentableTrait;

	protected $table = 'plans_stats';

	protected $fillable = ['goal_id', 'user_id', 'type', 'course', 'score', 'fir',
		'gir', 'putts', 'penalties', 'notes', 'balls', 'minutes', 'holes', 'players',
		'place', 'icon', 'tournament', 'stat_id'];

	protected $dates = ['created_at', 'updated_at'];

	protected $touches = ['plan'];

	protected $presenter = 'Plans\Data\Presenters\StatPresenter';

	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/

	public function goal()
	{
		return $this->belongsTo('Goal');
	}

	public function message()
	{
		return $this->hasMany('Stat', 'stat_id');
	}

	public function plan()
	{
		return $this->goal->plan();
	}

	public function user()
	{
		return $this->belongsTo('UserModel');
	}
	
}
