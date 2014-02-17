@extends('layouts.master')

@section('title')
	Event Details - {{ $event->name }}
@stop

@section('content')
	<h1>{{ $event->name }}</h1>

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
		<div class="col-sm-6 col-md-5 col-lg-5">
			<dl>
				<dt>Date(s)</dt>
				@foreach ($event->serviceOccurrences as $o)
					<dd>{{ $o->start->format('l M jS, Y') }}, {{ $o->start->format('g:ia') }} - {{ $o->end->format('g:ia') }}</dd>
				@endforeach

				<dt>Location</dt>
				<dd>{{ $appointment->location }}</dd>

				<dt>Price</dt>
				<dd>${{ $event->price }}</dd>

				<dt>Instructor</dt>
				<dd>{{ $event->staff->user->name }}</dd>
			</dt>
		</div>

		@if ($_currentUser)
			<div class="col-sm-6 col-md-4 col-lg-4">
				<dl>
					@if ($_currentUser->isStaff())
						<dt>Attendees ({{ $event->attendees()->count() }}/{{ $event->user_limit }})</dt>
						@foreach ($appointment->userAppointments as $ua)
							<dd>
								@if ((bool) $ua->paid)
									<span class="label label-success icn-size-16">{{ $_icons['check'] }}</span>
								@else
									<span class="label label-warning icn-size-16">{{ $_icons['warning'] }}</span>
								@endif

								{{ $ua->user->name }}
							</dd>
						@endforeach
					@endif
				</dt>
			</div>

			<div class="col-sm-12 col-md-3 col-lg-3">
				<div class="visible-md visible-lg">
					@if ($_currentUser != $event->staff->user)
						@if ( ! $_currentUser->isAttending($appointment->id))
							<a href="#" class="btn btn-block btn-primary js-enroll" data-appointment="{{ $appointment->id }}">Enroll Now</a>
						@endif

						@if ($_currentUser->isAttending($appointment->id))
							<a href="#" class="btn btn-block btn-danger js-withdraw" data-appointment="{{ $_currentUser->getAppointment($appointment->id)->first()->id }}">Withdraw</a>
						@endif
					@endif

					@if ($_currentUser->isStaff())
						<a href="{{ URL::route('admin.service.edit', array($event->id)) }}" class="btn btn-block btn-default">Manage</a>

						@if ($appointment->service->isLesson())
							<a href="#" class="btn btn-block btn-primary">Mark as Paid</a>
						@endif
					@endif
				</div>
				<div class="visible-xs visible-sm">
					@if ($_currentUser != $event->staff->user)
						@if ( ! $_currentUser->isAttending($appointment->id))
							<p><a href="#" class="btn btn-block btn-lg btn-primary js-enroll" data-appointment="{{ $appointment->id }}">Enroll Now</a></p>
						@endif

						@if ($_currentUser->isAttending($appointment->id))
							<p><a href="#" class="btn btn-block btn-lg btn-danger js-withdraw" data-appointment="{{ $_currentUser->getAppointment($appointment->id)->first()->id }}">Withdraw</a></p>
						@endif
					@endif

					@if ($_currentUser->isStaff())
						<p><a href="{{ URL::route('admin.service.edit', array($event->id)) }}" class="btn btn-block btn-lg btn-default">Manage</a></p>

						@if ($appointment->service->isLesson())
							<a href="#" class="btn btn-block btn-lg btn-primary">Mark as Paid</a>
						@endif
					@endif
				</div>
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