@extends('layouts.master')

@section('title')
	My Development Plan
@stop

@section('content')
	<h1>My Development Plan</h1>

	<div class="visible-xs visible-sm">
		<div class="row">
			<div class="col-xs-6 col-sm-3">
				<p><a href="#" class="btn btn-block btn-lg btn-primary">Add New Goal</a></p>
			</div>
			<div class="col-xs-6 col-sm-3">
				<p><a class="btn btn-block btn-lg btn-primary js-toggleGoals">Only Show My Goals</a></p>
			</div>
		</div>
	</div>
	<div class="visible-md visible-lg">
		<div class="btn-toolbar">
			<div class="btn-group">
				<a href="#" class="btn btn-sm btn-primary icn-size-16">{{ $_icons['add'] }}</a>
			</div>
			<div class="btn-group">
				<a href="#" class="btn btn-sm btn-primary icn-size-16-with-text js-toggleGoals">Only Show My Goals</a>
			</div>
		</div>
	</div>

	{{ partial('timeline-plan', ['items' => $timeline, 'plan' => $plan]) }}
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
	</script>
@stop