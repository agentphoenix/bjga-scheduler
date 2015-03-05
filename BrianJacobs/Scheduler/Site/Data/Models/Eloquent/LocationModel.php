<?php namespace Scheduler\Data\Models\Eloquent;

use Model;
use Laracasts\Presenter\PresentableTrait;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class LocationModel extends Model {

	use PresentableTrait;
	use SoftDeletingTrait;

	protected $table = 'locations';

	protected $fillable = ['name', 'address', 'phone', 'url'];

	protected $dates = ['created_at', 'updated_at', 'deleted_at', 'expires'];

	protected $presenter = 'Scheduler\Data\Presenters\LocationPresenter';

	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/

	public function staff()
	{
		return $this->hasMany('StaffScheduleModel', 'location_id');
	}

	public function appointments()
	{
		return $this->hasMany('StaffAppointmentModel', 'location_id');
	}

	public function services()
	{
		return $this->hasMany('ServiceModel', 'location_id');
	}

	/*
	|--------------------------------------------------------------------------
	| Getters/Setters
	|--------------------------------------------------------------------------
	*/

	public function setPhoneAttribute($value)
	{
		$this->attributes['phone'] = preg_replace('~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~', '$1-$2-$3', $value);
	}
	
}