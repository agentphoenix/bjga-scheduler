<?php namespace Scheduler\Api;

use API, Config, Route;
use Illuminate\Support\ServiceProvider;

class SchedulerApiServiceProvider extends ServiceProvider {

	public function register()
	{
		//
	}

	public function boot()
	{
		$this->routes();
		$this->setupTransformers();
	}

	protected function routes()
	{
		$options = [
			'version'	=> 'v1',
			'prefix'	=> 'api',
			'namespace'	=> 'Scheduler\Api\Controllers',
		];

		Route::api($options, function()
		{
			Route::get('services', 'ServicesController@index');
			Route::get('services/category/{category}', 'ServicesController@showByCategory');
			Route::get('services/name/{name}', 'ServicesController@showByName');
		});
	}

	protected function setupTransformers()
	{
		$a = Config::get('app.aliases');

		API::transform($a['ServiceModel'], 'Scheduler\Api\Transformers\ServiceTransformer');
	}

}