<?php

$options = [
	'namespace' => "Plans\\Controllers",
	'before'	=> "auth",
];

$adminOptions = [
	'namespace' => "Plans\\Controllers",
	'before'	=> "auth",
	'prefix'	=> 'admin',
];

Route::group($adminOptions, function()
{
	Route::get('plan/{id}/remove', [
		'as'	=> 'admin.plan.remove',
		'uses'	=> 'PlanController@remove']);
	Route::post('plan/remove-instructor', [
		'as'	=> 'admin.plan.removeInstructor',
		'uses'	=> 'PlanController@removeInstructor']);

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
	Route::resource('stats', 'StatsController', ['except' => ['index', 'show']]);
});

Route::group($options, function()
{
	Route::get('plan/{userId}/goal/{goalId}', [
		'as'	=> 'goal.show',
		'uses'	=> 'GoalController@show']);
	Route::get('goal/{id}/remove', [
		'as'	=> 'goal.remove',
		'uses'	=> 'GoalController@remove']);
	Route::get('goal/{id}/create', [
		'as'	=> 'goal.create',
		'uses'	=> 'GoalController@create']);
	Route::post('goal/{id}/update-status', [
		'as'	=> 'goal.update-status',
		'uses'	=> 'GoalController@changeStatus']);

	Route::resource('goal', 'GoalController', ['except' => ['index']]);

	Route::get('plan/{userId}', [
		'as'	=> 'plan',
		'uses'	=> 'PlanController@show']);
});
