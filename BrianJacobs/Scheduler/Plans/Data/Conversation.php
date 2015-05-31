<?php namespace Plans\Data;

use Model;
use Laracasts\Presenter\PresentableTrait;

class Conversation extends Model {

	use PresentableTrait;

	protected $table = 'plans_conversations';

	protected $fillable = ['goal_id', 'user_id', 'content'];

	protected $dates = ['created_at', 'updated_at'];

	protected $touches = ['plan'];

	protected $presenter = 'Plans\Data\Presenters\ConversationPresenter';

	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/

	public function goal()
	{
		return $this->belongsTo('Goal');
	}

	public function plan()
	{
		return $this->goal->plan();
	}

	public function user()
	{
		return $this->belongsTo('UserModel', 'user_id');
	}
	
}
