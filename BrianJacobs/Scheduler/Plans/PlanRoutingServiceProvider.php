<?php namespace Plans;

use Route;
use Illuminate\Support\ServiceProvider;

class PlanRoutingServiceProvider extends ServiceProvider {

	protected $options = [
		'namespace' => "Plans\\Controllers",
		'before'	=> "auth",
	];

	protected $adminOptions = [
		'namespace' => "Plans\\Controllers\\Admin",
		'before'	=> "auth",
		'prefix'	=> 'admin',
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
		Route::group($this->adminOptions, function()
		{
			Route::get('plan/{id}/remove', [
				'as'	=> 'admin.plan.remove',
				'uses'	=> 'PlanController@remove']);
			Route::post('plan/remove-instructor', [
				'as'	=> 'admin.plan.removeInstructor',
				'uses'	=> 'PlanController@removeInstructor']);

			Route::get('goal/{id}/remove', [
				'as'	=> 'admin.goal.remove',
				'uses'	=> 'GoalController@remove']);
			Route::get('goal/{id}/create', [
				'as'	=> 'admin.goal.create',
				'uses'	=> 'GoalController@create']);
			Route::post('goal/{id}/update-status', [
				'as'	=> 'admin.goal.update-status',
				'uses'	=> 'GoalController@changeStatus']);

			Route::get('conversation/{goalId}/create', [
				'as'	=> 'admin.conversation.create',
				'uses'	=> 'ConversationController@create']);
			Route::post('conversation/{goalId}', [
				'as'	=> 'admin.conversation.store',
				'uses'	=> 'ConversationController@store']);
			Route::get('conversation/{id}/remove', [
				'as'	=> 'admin.conversation.remove',
				'uses'	=> 'ConversationController@remove']);
			Route::delete('conversation/{id}', [
				'as'	=> 'admin.conversation.destroy',
				'uses'	=> 'ConversationController@destroy']);

			Route::get('stats/{goalId}/create', [
				'as'	=> 'admin.stats.create',
				'uses'	=> 'StatsController@create']);
			Route::post('stats/{goalId}', [
				'as'	=> 'admin.stats.store',
				'uses'	=> 'StatsController@store']);

			Route::resource('plan', 'PlanController', ['except' => ['show']]);
			Route::resource('goal', 'GoalController', ['except' => ['show']]);
			Route::resource('stats', 'StatsController', ['except' => ['index', 'show']]);
		});
	}

	protected function routes()
	{
		Route::group($this->options, function()
		{
			/*Route::get('my-plan', [
				'as'	=> 'my-plan',
				'uses'	=> 'PlanController@show']);
			Route::get('my-plan/goal/{id}', [
				'as'	=> 'my-plan.goal',
				'uses'	=> 'PlanController@goal']);*/

			Route::get('plan/{userId?}/goal/{goalId}', [
				'as'	=> 'plan.goal',
				'uses'	=> 'PlanController@goal']);
			Route::get('plan/{userId?}', [
				'as'	=> 'plan',
				'uses'	=> 'PlanController@show']);
		});
	}

}
