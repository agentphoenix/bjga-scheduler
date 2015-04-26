<?php namespace Plans;

use Route;
use Illuminate\Support\ServiceProvider;

class PlanRoutingServiceProvider extends ServiceProvider {

	protected $options = [
		'namespace' => "Plans\\Controllers",
		'before'	=> "auth",
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
				'uses'	=> 'PlanController@show']);
			Route::get('my-plan/goal/{id}', [
				'as'	=> 'my-plan.goal',
				'uses'	=> 'PlanController@goal']);

			Route::get('plan/{id}', [
				'as'	=> 'plan',
				'uses'	=> 'PlanController@show']);

			Route::get('admin/plan/{id}/remove', [
				'as'	=> 'admin.plan.remove',
				'uses'	=> 'Admin\PlanController@remove']);
			Route::post('admin/plan/remove-instructor', [
				'as'	=> 'admin.plan.removeInstructor',
				'uses'	=> 'Admin\PlanController@removeInstructor']);

			Route::resource('admin/plan', 'Admin\PlanController', ['except' => ['show']]);
		});
	}

}
