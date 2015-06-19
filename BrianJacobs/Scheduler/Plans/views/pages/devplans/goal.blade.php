@extends('layouts.master')

@section('title')
	{{ $goal->present()->title }} &bull; {{ ($userId) ? $user->present()->name."'s Development Plan" : "My Development Plan" }}
@stop

@section('content')
	<h1>{{ $goal->present()->title }} <small>{{ ($userId) ? $user->present()->name."'s Development Plan" : "My Development Plan" }}</small></h1>

	<div class="visible-xs visible-sm">
		<div class="row">
			<div class="col-xs-12 col-sm-4">
				<p><a href="{{ route('plan', [$userId]) }}" class="btn btn-block btn-lg btn-default">{{ ($userId) ? "Back to Plan" : "Back to My Plan" }}</a></p>
			</div>
			@if ( ! (bool) $goal->completed)
				<div class="col-xs-6 col-sm-4">
					<p><a href="#" class="btn btn-block btn-lg btn-primary js-goalAction" data-action="comment-add" data-item="{{ $goal->id }}">Add Comment</a></p>
				</div>
				<div class="col-xs-6 col-sm-4">
					<p><a href="#" class="btn btn-block btn-lg btn-primary js-goalAction" data-action="stats-add" data-item="{{ $goal->id }}">Add Stats</a></p>
				</div>
			@endif
		</div>
	</div>
	<div class="visible-md visible-lg">
		<div class="btn-toolbar">
			<div class="btn-group">
				<a href="{{ route('plan', [$userId]) }}" class="btn btn-sm btn-default icn-size-16">{{ $_icons['back'] }}</a>
			</div>
			@if ( ! (bool) $goal->completed)
				<div class="btn-group">
					<button type="button" class="btn btn-sm btn-primary icn-size-16 dropdown-toggle" data-toggle="dropdown">{{ $_icons['add'] }}</button>
					<ul class="dropdown-menu" role="menu">
						<li><a href="#" class="js-goalAction" data-action="comment-add" data-item="{{ $goal->id }}">Add Comment</a></li>
						<li><a href="#" class="js-goalAction" data-action="stats-add" data-item="{{ $goal->id }}">Add Stats</a></li>
					</ul>
				</div>
			@endif
		</div>
	</div>

	{{ partial('timeline-goal', ['items' => $timeline, 'goal' => $goal, 'userId' => $userId]) }}
@stop

@section('modals')
	{{ modal(['id' => 'addComment', 'header' => 'Add a Comment']) }}
	{{ modal(['id' => 'editComment', 'header' => 'Edit Comment']) }}
	{{ modal(['id' => 'removeComment', 'header' => 'Remove Comment']) }}
	{{ modal(['id' => 'addStats', 'header' => 'Add Stats']) }}
	{{ modal(['id' => 'editStats', 'header' => 'Edit Stats']) }}
	{{ modal(['id' => 'removeStats', 'header' => 'Remove Stats']) }}
	{{ modal(['id' => 'associateGoal', 'header' => 'Associate Lesson with Development Plan Goal']) }}
@stop

@section('styles')
	{{ HTML::style('css/timeline.css') }}
	{{ HTML::script('js/modernizr.js') }}
@stop

@section('scripts')
	<script>
		$(function($)
		{
			var $timeline_block = $('.cd-timeline-block.unchanged');

			// Hide timeline blocks which are outside the viewport
			$timeline_block.each(function()
			{
				if ($(this).offset().top > $(window).scrollTop() + $(window).height() * 0.95)
				{
					$(this).find('.cd-timeline-img, .cd-timeline-content').addClass('is-hidden');
				}
			});

			// On scolling, show/animate timeline blocks when enter the viewport
			$(window).on('scroll', function()
			{
				$timeline_block.each(function()
				{
					if ($(this).offset().top <= $(window).scrollTop() + $(window).height() * 0.95 && 
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

			if (text == "Show Only Goals")
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
				$(this).text("Show Only Goals");
			}
		});

		$('.js-goalAction').on('click', function(e)
		{
			e.preventDefault();

			var action = $(this).data('action');
			var item = $(this).data('item');

			if (action == "comment-add")
			{
				$('#addComment').modal({
					remote: "{{ URL::to('comment') }}/" + item + "/create"
				}).modal('show');
			}

			if (action == "comment-edit")
			{
				$('#editComment').modal({
					remote: "{{ URL::to('comment') }}/" + item + "/edit"
				}).modal('show');
			}

			if (action == "comment-remove")
			{
				$('#removeComment').modal({
					remote: "{{ URL::to('comment') }}/" + item + "/remove"
				}).modal('show');
			}

			if (action == "stats-add")
			{
				$('#addStats').modal({
					remote: "{{ URL::to('stats') }}/" + item + "/create"
				}).modal('show');
			}

			if (action == "stats-edit")
			{
				$('#editStats').modal({
					remote: "{{ URL::to('stats') }}/" + item + "/edit"
				}).modal('show');
			}

			if (action == "stats-remove")
			{
				$('#removeStats').modal({
					remote: "{{ URL::to('stats') }}/" + item + "/remove"
				}).modal('show');
			}

			if (action == "lesson-remove")
			{
				$.ajax({
					type: "POST",
					dataType: "json",
					url: "{{ route('lessons.removeGoal') }}",
					data: {
						"_token": "{{ csrf_token() }}",
						lesson: item
					},
					success: function(data)
					{
						window.location.reload();
					}
				});
			}
		});

		$('.js-goalAssociation').on('click', function(e)
		{
			e.preventDefault();

			var lesson = $(this).data('item');

			$('#associateGoal').modal({
				remote: "{{ URL::to('ajax/associate-goal') }}/" + lesson
			}).modal('show');
		});
	</script>
@stop