<?php namespace Scheduler\Api;

use Route;
use Illuminate\Support\ServiceProvider;

class SchedulerApiServiceProvider extends ServiceProvider {

	public function register()
	{
		//
	}

	public function boot()
	{
		$this->routes();
	}

	protected function routes()
	{
		Route::group(array('prefix' => 'api/v1'), function()
		{
			Route::get('services', 'Scheduler\Api\Controllers\ServicesController@index');
			Route::get('services/category/{category}', 'Scheduler\Api\Controllers\ServicesController@showByCategory');
			Route::get('services/name/{name}', 'Scheduler\Api\Controllers\ServicesController@showByName');
		});
	}

}