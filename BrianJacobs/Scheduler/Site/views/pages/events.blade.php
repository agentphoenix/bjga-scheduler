@extends('layouts.master')

@section('title')
	Upcoming Events
@stop

@section('content')
	<h1>Upcoming Events</h1>

	@if ($events->count() > 0)
		<article id="cd-timeline" class="cd-container">
			@foreach ($events as $event)
				<?php $appt = $event->getFirstAppointment();?>

				<div class="cd-timeline-block unchanged cd-timeline-goal">
					<div class="cd-timeline-img">
						<span class="icn-size-32">{{ $_icons['calendar'] }}</span>
					</div> <!-- cd-timeline-img -->

					<div class="cd-timeline-content">
						<h2>
							{{ $event->present()->name }}
							<small>{{ $event->present()->price }}</small>
						</h2>

						{{ $event->present()->summary }}

						<div class="visible-xs visible-sm">
							<p><a href="{{ route('event', array($event->slug)) }}" class="btn btn-default btn-lg btn-block">More Info</a></p>
							@if (Auth::check())
								@if ( ! $_currentUser->isAttending($event->id))
									<p><a href="#" class="btn btn-primary btn-lg btn-block js-enroll" data-service="{{ $event->id }}">Enroll</a></p>
								@else
									<p><a href="#" class="btn btn-danger btn-lg btn-block js-withdraw" data-service="{{ $event->id }}">Withdraw</a></p>
								@endif
							@endif
						</div>
						<div class="visible-md visible-lg">
							<div class="btn-toolbar">
								<div class="btn-group">
									<a href="{{ route('event', array($event->slug)) }}" class="btn btn-default btn-sm">More Info</a>
								</div>

								@if (Auth::check())
									@if ( ! $_currentUser->isAttending($event->id))
										<div class="btn-group">
											<a href="#" class="btn btn-primary btn-sm js-enroll" data-service="{{ $event->id }}">Enroll</a>
										</div>
									@else
										<div class="btn-group">
											<a href="#" class="btn btn-danger btn-sm js-withdraw" data-service="{{ $event->id }}">Withdraw</a>
										</div>
									@endif
								@endif
							</div>
						</div>
						<span class="cd-date">
							@if ($event->occurrences > 1)
								Begins on
							@endif
							{{ $appt->start->format(Config::get('bjga.dates.date')) }}
						</span>
					</div> <!-- cd-timeline-content -->
				</div> <!-- cd-timeline-block -->
			@endforeach
		</article>
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

		$(document).on('click', '.js-enroll', function(e)
		{
			e.preventDefault();

			$.ajax({
				url: "{{ route('book.enroll') }}",
				type: "POST",
				data: {
					service: $(this).data('service')
				},
				success: function(data)
				{
					location.reload();
				}
			});
		});

		$(document).on('click', '.js-withdraw', function(e)
		{
			e.preventDefault();

			$.ajax({
				type: "POST",
				data: {
					service: $(this).data('service')
				},
				url: "{{ route('ajax.withdraw') }}",
				success: function (data)
				{
					location.reload();
				}
			});
		});
	</script>
@stop