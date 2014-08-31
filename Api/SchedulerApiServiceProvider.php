<?php namespace Scheduler\Api;

use API,
	Route,
	Config;
use Illuminate\Support\ServiceProvider;

class SchedulerApiServiceProvider extends ServiceProvider {

	public function register()
	{
		$this->setApiTransformers();
	}

	public function boot()
	{
		$this->routes();
	}

	protected function setApiTransformers()
	{
		$a = Config::get('app.aliases');

		API::transform($a['ServiceModel'], $a['ServiceTransformer']);
	}

	protected function routes()
	{
		Route::api(['version' => 'v1', 'prefix' => 'api'], function()
		{
			Route::get('services', 'Scheduler\Api\Controllers\ServicesController@index');
			Route::get('services/category/{category}', 'Scheduler\Api\Controllers\ServicesController@showByCategory');
			Route::get('services/name/{name}', 'Scheduler\Api\Controllers\ServicesController@showByName');
		});
	}

}