<?php namespace Plans\Data;

use Model;
use Laracasts\Presenter\PresentableTrait;

class Conversation extends Model {

	use PresentableTrait;

	protected $table = 'development_plans_conversations';

	protected $fillable = ['plan_id', 'goal_id', 'user_id', 'content'];

	protected $dates = ['created_at', 'updated_at'];

	protected $presenter = 'Plans\Data\Presenters\ConversationPresenter';

	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/

	public function plan()
	{
		return $this->belongsTo('Plan');
	}

	public function goal()
	{
		return $this->belongsTo('Goal');
	}

	public function user()
	{
		return $this->belongsTo('UserModel', 'user_id');
	}
	
}
