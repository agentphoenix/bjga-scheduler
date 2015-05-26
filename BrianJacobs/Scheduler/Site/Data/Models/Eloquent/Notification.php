<?php namespace Scheduler\Data\Models\Eloquent;

use Model;
use Laracasts\Presenter\PresentableTrait;

class Notification extends Model {

	use PresentableTrait;

	protected $table = 'notifications';

	protected $fillable = ['user_id', 'type', 'category', 'action', 'content'];

	protected $dates = ['created_at', 'updated_at'];

	protected $presenter = 'Scheduler\Data\Presenters\NotificationPresenter';

	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/

	public function user()
	{
		return $this->belongsTo('UserModel');
	}
	
}
