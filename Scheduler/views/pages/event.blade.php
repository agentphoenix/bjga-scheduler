@extends('layouts.master')

@section('title')
	Program Details - {{ $event->name }}
@stop

@section('content')
	<h1>{{ $event->name }}</h1>

	<div class="visible-md visible-lg">
		<div class="btn-toolbar">
			<div class="btn-group">
				<a href="{{ URL::route('events') }}" class="btn btn-sm btn-default icn-size-16">{{ $_icons['back'] }}</a>
			</div>

			@if ( ! $_currentUser->isAttending($event->id))
				<div class="btn-group">
					<a href="#" class="btn btn-primary icn-size-16 js-enroll icn-size-16-with-text" data-service="{{ $event->id }}">Enroll Now</a>
				</div>
			@endif

			@if ($_currentUser->isAttending($event->id))
				<div class="btn-group">
					<a href="#" class="btn btn-danger icn-size-16 js-withdraw icn-size-16-with-text" data-appointment="{{ $_currentUser->getAppointment($appointment->id)->first()->id }}">Withdraw Now</a>
				</div>
			@endif
		</div>
	</div>
	<div class="visible-xs visible-sm">
		<div class="row">
			<div class="col-xs-6 col-sm-3">
				<p><a href="{{ URL::route('events') }}" class="btn btn-lg btn-block btn-default icn-size-16">{{ $_icons['back'] }}</a></p>
			</div>
			@if ( ! $_currentUser->isAttending($event->id))
				<div class="col-xs-6 col-sm-3">
					<p><a href="#" class="btn btn-lg btn-block btn-primary icn-size-16-with-text js-enroll" data-service="{{ $event->id }}">Enroll Now</a></p>
				</div>
			@endif

			@if ($_currentUser->isAttending($event->id))
				<div class="col-xs-6 col-sm-3">
					<p><a href="#" class="btn btn-lg btn-block btn-danger icn-size-16-with-text js-withdraw" data-appointment="{{ $_currentUser->getAppointment($appointment->id)->first()->id }}">Withdraw Now</a></p>
				</div>
			@endif
		</div>
	</div>

	<p>{{ $event->description }}</p>

	@if ( ! empty($appointment->notes) and $_currentUser and $_currentUser->isStaff())
		<div class="panel panel-warning">
			<div class="panel-heading">
				<h3 class="panel-title">Notes</h3>
			</div>
			<div class="panel-body">
				{{ $appointment->notes }}
			</div>
		</div>
	@endif

	<div class="row">
		<div class="col-sm-2 col-md-2 col-lg-2">
			<h2>Price</h2>
			<h3 class="text-success price-details">
				@if ($event->price > 0)
					@if ($event->occurrences > 1 and $event->isLesson())
						${{ round(($event->price * $event->occurrences) / ($event->occurrences / 4), 2) }} <small>per month</small>
					@else
						${{ $event->price }}
					@endif
				@else
					Free
				@endif
			</h3>
		</div>
		<div class="col-sm-3 col-md-3 col-lg-3">
			<h2>Instructor</h2>
			<p>{{ $event->staff->user->name }}</p>
		</div>
		<div class="col-sm-7 col-md-7 col-lg-7">
			<h2>Date(s)</h2>

			@foreach ($event->serviceOccurrences as $o)
				<p>{{ $o->start->format(Config::get('bjga.dates.date')) }}, {{ $o->start->format(Config::get('bjga.dates.time')) }} - {{ $o->end->format(Config::get('bjga.dates.time')) }}</p>
			@endforeach
		</div>
	</div>
@stop

@section('scripts')
	<script>
		
		$(document).on('click', '.js-enroll', function(e)
		{
			e.preventDefault();

			$.ajax({
				url: "{{ URL::route('book.enroll') }}",
				type: "POST",
				data: { service: $(this).data('service') },
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
					'appointment': $(this).data('appointment')
				},
				url: "{{ URL::route('ajax.withdraw') }}",
				success: function(data)
				{
					location.reload();
				}
			});
		});

	</script>
@stop