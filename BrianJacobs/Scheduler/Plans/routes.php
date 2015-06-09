<?php

$options = [
	'namespace' => "Plans\\Controllers",
	'before'	=> "auth",
];

$adminOptions = [
	'namespace' => "Plans\\Controllers",
	'before'	=> "auth",
];

Route::group($adminOptions, function()
{
	Route::get('admin/plan', [
		'as'	=> 'plan.index',
		'uses'	=> 'PlanController@index']);
	Route::get('plan/{id}/remove', [
		'as'	=> 'plan.remove',
		'uses'	=> 'PlanController@remove']);
	Route::post('plan/remove-instructor', [
		'as'	=> 'plan.removeInstructor',
		'uses'	=> 'PlanController@removeInstructor']);

	Route::get('conversation/{goalId}/create', [
		'as'	=> 'conversation.create',
		'uses'	=> 'ConversationController@create']);
	Route::post('conversation/{goalId}', [
		'as'	=> 'conversation.store',
		'uses'	=> 'ConversationController@store']);
	Route::get('conversation/{commentId}/edit', [
		'as'	=> 'conversation.edit',
		'uses'	=> 'ConversationController@edit']);
	Route::put('conversation/{commentId}', [
		'as'	=> 'conversation.update',
		'uses'	=> 'ConversationController@update']);
	Route::get('conversation/{id}/remove', [
		'as'	=> 'conversation.remove',
		'uses'	=> 'ConversationController@remove']);
	Route::delete('conversation/{id}', [
		'as'	=> 'conversation.destroy',
		'uses'	=> 'ConversationController@destroy']);

	Route::get('stats/{goalId}/create', [
		'as'	=> 'stats.create',
		'uses'	=> 'StatsController@create']);
	Route::post('stats/{goalId}', [
		'as'	=> 'stats.store',
		'uses'	=> 'StatsController@store']);
	Route::get('stats/{id}/remove', [
		'as'	=> 'stats.remove',
		'uses'	=> 'StatsController@remove']);

	Route::resource('plan', 'PlanController', ['except' => ['show']]);
	Route::resource('stats', 'StatsController', ['except' => ['index', 'show']]);

	Route::post('remove-lesson-goal-association', [
		'as'	=> 'lessons.removeGoal',
		'uses'	=> 'GoalController@removeLessonGoalAssociation']);
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

	Route::resource('goal', 'GoalController', ['except' => ['index', 'show']]);

	Route::get('plan/{userId}', [
		'as'	=> 'plan',
		'uses'	=> 'PlanController@show']);
});
