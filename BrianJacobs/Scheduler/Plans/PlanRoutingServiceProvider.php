<?php namespace Plans;

use Route;
use Illuminate\Support\ServiceProvider;

class PlanRoutingServiceProvider extends ServiceProvider {

	protected $options = [
		'namespace' => "Plans\\Controllers"
	];

	public function register()
	{
		//
	}

	public function boot()
	{
		$this->adminRoutes();
		$this->routes();
	}

	protected function adminRoutes()
	{
		# code...
	}

	protected function routes()
	{
		Route::group($this->options, function()
		{
			Route::get('my-plan', [
				'as'	=> 'my-plan',
				'uses'	=> 'PlanController@myPlan']);
		});
	}

}
