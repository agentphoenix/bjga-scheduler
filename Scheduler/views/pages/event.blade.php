@extends('layouts.master')

@section('title')
	Event Details - {{ $event->name }}
@stop

@section('content')
	<h1>{{ $event->name }}</h1>

	<div class="visible-md visible-lg">
		<div class="btn-toolbar">
			<div class="btn-group">
				<a href="{{ URL::route('events') }}" class="btn btn-sm btn-default icn-size-16">{{ $_icons['back'] }}</a>
			</div>

			@if ( ! $_currentUser->isAttending($appointment->id))
				<div class="btn-group">
					<a href="#" class="btn btn-primary icn-size-16 js-enroll" data-appointment="{{ $appointment->id }}">Enroll Now</a>
				</div>
			@endif

			@if ($_currentUser->isAttending($appointment->id))
				<div class="btn-group">
					<a href="#" class="btn btn-danger icn-size-16 js-withdraw" data-appointment="{{ $_currentUser->getAppointment($appointment->id)->first()->id }}">Withdraw Now</a>
				</div>
			@endif
		</div>
	</div>
	<div class="visible-xs visible-sm">
		<div class="row">
			<div class="col-xs-12 col-sm-3">
				<p><a href="{{ URL::route('events') }}" class="btn btn-lg btn-block btn-default icn-size-16">{{ $_icons['back'] }}</a></p>
			</div>
			@if ( ! $_currentUser->isAttending($appointment->id))
				<div class="col-xs-12 col-sm-3">
					<p><a href="#" class="btn btn-lg btn-block btn-primary icn-size-16 js-enroll" data-appointment="{{ $appointment->id }}">Enroll Now</a></p>
				</div>
			@endif

			@if ($_currentUser->isAttending($appointment->id))
				<div class="col-xs-12 col-sm-3">
					<p><a href="#" class="btn btn-lg btn-block btn-danger icn-size-16 js-withdraw" data-appointment="{{ $_currentUser->getAppointment($appointment->id)->first()->id }}">Withdraw Now</a></p>
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
		<div class="col-sm-3 col-md-3 col-lg-3">
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
		<div class="col-sm-6 col-md-6 col-lg-6">
			<h2>Date(s)</h2>

			@foreach ($event->serviceOccurrences as $o)
				<p>{{ $o->start->format(Config::get('bjga.dates.date')) }}, {{ $o->start->format(Config::get('bjga.dates.time')) }} - {{ $o->end->format(Config::get('bjga.dates.time')) }}</p>
			@endforeach
		</div>
	</div>
@stop

@section('scripts')
	<script type="text/javascript">
		
		$(document).on('click', '.js-enroll', function(e)
		{
			e.preventDefault();

			$.ajax({
				type: "POST",
				data: {
					'appointment': $(this).data('appointment')
				},
				url: "{{ URL::route('ajax.enroll') }}",
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