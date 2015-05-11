@extends('layouts.master')

@section('title')
	{{ ($userId) ? $user->present()->name."'s Development Plan" : "My Development Plan" }}
@stop

@section('content')
	<h1>{{ ($userId) ? $user->present()->name."'s Development Plan" : "My Development Plan" }}</h1>

	<div class="visible-xs visible-sm">
		<div class="row">
			<div class="col-xs-12 col-sm-6">
				<p><a href="#" class="btn btn-block btn-lg btn-primary js-planAction" data-action="goal-add" data-item="{{ $plan->id }}">Add a Goal</a></p>
			</div>
			<div class="col-xs-12 col-sm-6">
				<p><a class="btn btn-block btn-lg btn-primary js-toggleGoals">Only Show Goals</a></p>
			</div>
		</div>
	</div>
	<div class="visible-md visible-lg">
		<div class="btn-toolbar">
			<div class="btn-group">
				<a href="#" class="btn btn-sm btn-primary icn-size-16 js-planAction" data-action="goal-add" data-item="{{ $plan->id }}">{{ $_icons['add'] }}</a>
			</div>
			<div class="btn-group">
				<a href="#" class="btn btn-sm btn-primary icn-size-16-with-text js-toggleGoals">Only Show Goals</a>
			</div>
		</div>
	</div>

	{{ partial('timeline-plan', ['items' => $timeline, 'plan' => $plan, 'userId' => $userId]) }}
@stop

@section('modals')
	{{ modal(['id' => 'addGoal', 'header' => "Add a Goal"]) }}
	{{ modal(['id' => 'editGoal', 'header' => "Edit Goal"]) }}
	{{ modal(['id' => 'removeGoal', 'header' => "Remove Goal"]) }}
@stop

@section('styles')
	{{ HTML::style('css/timeline.css') }}
	{{ HTML::script('js/modernizr.js') }}
@stop

@section('scripts')
	<script>
		jQuery(document).ready(function($)
		{
			var $timeline_block = $('.cd-timeline-block.unchanged');

			// Hide timeline blocks which are outside the viewport
			$timeline_block.each(function()
			{
				if ($(this).offset().top > $(window).scrollTop() + $(window).height() * 0.85)
				{
					$(this).find('.cd-timeline-img, .cd-timeline-content').addClass('is-hidden');
				}
			});

			// On scolling, show/animate timeline blocks when enter the viewport
			$(window).on('scroll', function()
			{
				$timeline_block.each(function()
				{
					if ($(this).offset().top <= $(window).scrollTop() + $(window).height() * 0.85 && 
							$(this).find('.cd-timeline-img').hasClass('is-hidden'))
					{
						$(this).find('.cd-timeline-img, .cd-timeline-content')
							.removeClass('is-hidden')
							.addClass('bounce-in');

						$timeline_block.removeClass('unchanged');
					}
				});
			});
		});

		$('.js-toggleGoals').on('click', function(e)
		{
			e.preventDefault();

			var text = $(this).text();

			if (text == "Only Show My Goals")
			{
				$('.cd-timeline-block:not(.cd-timeline-goal)').addClass('hide');
				$('.cd-timeline-block.cd-timeline-goal')
					.find('.cd-timeline-img, .cd-timeline-content')
					.removeClass('is-hidden');
				$(this).text("Show All Events");
			}
			else
			{
				$('.cd-timeline-block:not(.cd-timeline-goal)').removeClass('hide');
				$(this).text("Only Show My Goals");
			}
		});

		$('.js-planAction').on('click', function(e)
		{
			e.preventDefault();

			var action = $(this).data('action');
			var item = $(this).data('item');

			if (action == 'goal-add')
			{
				$('#addGoal').modal({
					remote: "{{ URL::to('admin/goal') }}/" + plan + "/create"
				}).modal('show');
			}

			if (action == 'goal-edit')
			{
				$('#editGoal').modal({
					remote: "{{ URL::to('admin/goal') }}/" + item + "/edit"
				}).modal('show');
			}
		});
	</script>
@stop