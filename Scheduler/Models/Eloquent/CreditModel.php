<?php namespace Scheduler\Models\Eloquent;

use Model;
use Laracasts\Presenter\PresentableTrait;

class CreditModel extends Model {

	use PresentableTrait;

	protected $table = 'users_credits';

	protected $fillable = ['code', 'type', 'value', 'claimed', 'user_id', 'email',
		'expires'];

	protected $softDelete = true;

	protected $dates = ['created_at', 'updated_at', 'deleted_at'];

	protected $presenter = 'Scheduler\Presenters\CreditPresenter';

	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/

	public function user()
	{
		return $this->belongsTo('UserModel', 'user_id');
	}
	
}