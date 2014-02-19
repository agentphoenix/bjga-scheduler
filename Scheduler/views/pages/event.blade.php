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
					<a href="#" class="btn btn-danger icn-size-16 js-withdraw" data-appointment="{{ $_currentUser->getAppointment($appointment->id)->first()->id }}">Withdraw</a>
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
					<p><a href="#" class="btn btn-lg btn-block btn-danger icn-size-16 js-withdraw" data-appointment="{{ $_currentUser->getAppointment($appointment->id)->first()->id }}">Withdraw</a></p>
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
		<div class="col-sm-8 col-md-8 col-lg-8">
			<dl>
				<dt>Date(s)</dt>
				@foreach ($event->serviceOccurrences as $o)
					<dd>{{ $o->start->format(Config::get('bjga.dates.date')) }}</dd>
					<dd>{{ $o->start->format(Config::get('bjga.dates.time')) }} - {{ $o->end->format(Config::get('bjga.dates.time')) }}</dd>
				@endforeach

				<dt>Price</dt>
				<dd>
					@if ($event->price > 0)
						${{ $event->price }}
					@else
						Free
					@endif
				</dd>

				<dt>Instructor</dt>
				<dd>{{ $event->staff->user->name }}</dd>
			</dl>
		</div>

		@if ($_currentUser)
			<div class="col-sm-4 col-md-4 col-lg-4">
				@if ( ! $_currentUser->isAttending($appointment->id))
					<p><a href="#" class="btn btn-lg btn-block btn-primary js-enroll" data-appointment="{{ $appointment->id }}">Enroll Now</a></p>
				@endif

				@if ($_currentUser->isAttending($appointment->id))
					<a href="#" class="btn btn-lg btn-block btn-danger js-withdraw" data-appointment="{{ $_currentUser->getAppointment($appointment->id)->first()->id }}">Withdraw</a>
				@endif
			</div>
		@endif
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