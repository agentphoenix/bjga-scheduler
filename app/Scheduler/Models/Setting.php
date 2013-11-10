<?php namespace Scheduler\Models;

use Model;

class Setting extends Model {

	protected $table = 'settings';

	protected $fillable = array(
		'key', 'value', 'label', 'description',
	);

	protected $dates = array(
		'created_at', 'updated_at',
	);
	
	protected static $properties = array(
		'id', 'key', 'value', 'label', 'description', 'created_at', 'updated_at',
	);
	
}