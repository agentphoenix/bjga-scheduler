@extends('layouts.master')

@section('title')
	Upcoming Programs
@stop

@section('content')
	<h1>Upcoming Programs</h1>

	@if ($events->count() > 0)
		<article id="cd-timeline" class="cd-container">
		@foreach ($events as $event)
			<div class="cd-timeline-block unchanged cd-timeline-goal">
				<div class="cd-timeline-img">
					<span class="icn-size-32">
						@if ((bool) $item->completed)
							{{ $_icons['check'] }}
						@else
							{{ $_icons['target'] }}
						@endif
					</span>
				</div> <!-- cd-timeline-img -->

				<div class="cd-timeline-content">
					<h2>
						{{ $item->present()->title }}
						@if ((bool) $item->completed)
							<small>Completed on {{ $item->present()->completedDate }}</small>
						@endif
					</h2>
					{{ $item->present()->summary }}
					<div class="visible-xs visible-sm">
						<p><a href="{{ route('plan.goal', [$userId, $item->id]) }}" class="btn btn-default btn-lg btn-block">View Goal</a></p>
					</div>
					<div class="visible-md visible-lg">
						<a href="{{ route('plan.goal', [$userId, $item->id]) }}" class="btn btn-default btn-sm">View Goal</a>
					</div>
					<span class="cd-date">{{ $item->present()->created }}</span>
				</div> <!-- cd-timeline-content -->
			</div> <!-- cd-timeline-block -->
		@endforeach
	</article>

		<div class="row">
			<div class="col-xs-12 col-sm-12 visible-xs visible-sm">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Icon Key</h3>
					</div>
					<div class="panel-body">
						<p>
							<span class="label label-default icn-size-16">{{ $_icons['calendar'] }}</span>&nbsp;
							<span class="text-muted text-sm">Program spans multiple days</span>
						</p>
						<p>
							<span class="label label-warning icn-size-16">{{ $_icons['warning'] }}</span>&nbsp;
							<span class="text-muted text-sm">Space limited, enroll today</span>
						</p>
						<p>
							<span class="label label-success icn-size-16">{{ $_icons['check'] }}</span>&nbsp;
							<span class="text-muted text-sm">You are currently enrolled</span>
						</p>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
		@foreach ($events as $event)
			<?php $appt = $event->appointments->first();?>
			<?php $openSlots = $event->user_limit - $event->attendees()->count();?>
			<?php $hasOpenings = $event->attendees()->count() < $event->user_limit;?>

			<div class="col-lg-6">
				<div class="well well-sm">
					<h3>
						{{ $event->name }}

						<small>{{ $event->present()->price }}</small>
					</h3>

					<div class="row">
						<div class="col-sm-6 col-md-6 col-lg-6">
							<p class="text-sm">
								<strong>
									@if ($appt->start->isToday())
										Today
									@elseif ($appt->start->isTomorrow())
										Tomorrow
									@else
										{{ $appt->start->format(Config::get('bjga.dates.date')) }}
									@endif
								</strong>
								
								@if ($event->occurrences > 1)
									&nbsp;<span class="label label-default icn-size-16 js-tooltip-bottom" data-title="Program spans multiple days">{{ $_icons['calendar'] }}</span>
								@endif

								@if ($hasOpenings and ($openSlots <= 5 and $openSlots > 0))
									&nbsp; <span class="label label-warning icn-size-16 js-tooltip-bottom" data-title="Space limited, enroll today">{{ $_icons['warning'] }}</span>
								@endif

								@if (Auth::check() and $_currentUser->isAttending($event->id))
									&nbsp;<span class="label label-success icn-size-16 js-tooltip-bottom" data-title="You are currently enrolled">{{ $_icons['check'] }}</span>
								@endif

								<br>
								<span class="text-muted">{{ $appt->start->format(Config::get('bjga.dates.time')) }} - {{ $appt->end->format(Config::get('bjga.dates.time')) }}</span>
							</p>
						</div>
						<div class="col-sm-6 col-md-6 col-lg-6">
							<div class="visible-md visible-lg">
								<p class="pull-right"><a href="{{ URL::route('event', array($event->slug)) }}" class="btn btn-lg btn-default">More Info</a></p>
							</div>
							<div class="visible-xs visible-sm">
								<div class="row">
									<div class="col-sm-6 col-sm-offset-6">
										<p><a href="{{ URL::route('event', array($event->slug)) }}" class="btn btn-lg btn-block btn-default">More Info</a></p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		@endforeach
		</div>
	@else
		{{ partial('common/alert', array('class' => ' alert-warning', 'content' => "There are no scheduled events in the next 90 days. Check back regularly for more events.")) }}
	@endif
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
	</script>
@stop