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
	Route::get('plan/{planId}/remove', [
		'as'	=> 'plan.remove',
		'uses'	=> 'PlanController@remove']);
	Route::post('plan/remove-instructor', [
		'as'	=> 'plan.removeInstructor',
		'uses'	=> 'PlanController@removeInstructor']);

	Route::get('comment/{goalId}/create', [
		'as'	=> 'comment.create',
		'uses'	=> 'CommentController@create']);
	Route::post('comment/{goalId}', [
		'as'	=> 'comment.store',
		'uses'	=> 'CommentController@store']);
	Route::get('comment/{commentId}/edit', [
		'as'	=> 'comment.edit',
		'uses'	=> 'CommentController@edit']);
	Route::put('comment/{commentId}', [
		'as'	=> 'comment.update',
		'uses'	=> 'CommentController@update']);
	Route::get('comment/{commentId}/remove', [
		'as'	=> 'comment.remove',
		'uses'	=> 'CommentController@remove']);
	Route::delete('comment/{commentId}', [
		'as'	=> 'comment.destroy',
		'uses'	=> 'CommentController@destroy']);

	Route::get('stats/{goalId}/create', [
		'as'	=> 'stats.create',
		'uses'	=> 'StatsController@create']);
	Route::post('stats/{goalId}', [
		'as'	=> 'stats.store',
		'uses'	=> 'StatsController@store']);
	Route::get('stats/{statId}/remove', [
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
	Route::get('goal/{goalId}/remove', [
		'as'	=> 'goal.remove',
		'uses'	=> 'GoalController@remove']);
	Route::get('goal/{planId}/create', [
		'as'	=> 'goal.create',
		'uses'	=> 'GoalController@create']);
	Route::post('goal/update-status', [
		'as'	=> 'goal.update-status',
		'uses'	=> 'GoalController@changeStatus']);

	Route::resource('goal', 'GoalController', ['except' => ['index', 'show']]);

	Route::get('plan/{userId}', [
		'as'	=> 'plan',
		'uses'	=> 'PlanController@show']);
});
