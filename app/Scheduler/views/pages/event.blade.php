@extends('layouts.master')

@section('title')
	Event Details - {{ $event->name }}
@endsection

@section('content')
	<h1>{{ $event->name }}</h1>

	<p>{{ $event->description }}</p>

	@if ( ! empty($appointment->notes) and $currentUser and $currentUser->isStaff())
		<div class="panel panel-warning">
			<div class="panel-heading">
				<h3 class="panel-title">Notes</h3>
			</div>
			<div class="panel-body">
				{{ $appointment->notes }}
			</div>
		</div>
	@endif

	<h2>Details</h2>

	<div class="row">
		<div class="col-md-5 col-lg-5">
			<dl>
				<dt>Date</dt>
				<dd>{{ Date::createFromFormat('Y-m-d', $appointment->date)->format('l F jS, Y') }}

				<dt>Time</dt>
				<dd>{{ Date::createFromFormat('H:i:s', $appointment->start_time)->format('g:ia') }} - {{ Date::createFromFormat('H:i:s', $appointment->end_time)->format('g:ia') }}</dd>

				<dt>Location</dt>
				<dd>{{ $appointment->location }}</dd>

				<dt>Price</dt>
				<dd>
					@if (Str::lower($event->price) == 'free')
						{{ $event->price }}
					@else
						${{ $event->price }}
					@endif
				</dd>

				<dt>Instructor</dt>
				<dd>{{ $event->staff->user->name }}</dd>
			</dt>
		</div>

		@if ($currentUser)
			<div class="col-md-4 col-lg-4">
				<dl>
					@if ($currentUser->isStaff())
						<dt>Attendees ({{ $appointment->attendees->count() }}/{{ $event->user_limit }})</dt>
						@foreach ($appointment->attendees as $attendee)
							<dd>
								@if ((bool) $attendee->paid)
									<span class="label label-success icn-size-16">!</span>
								@else
									<span class="label label-danger icn-size-16">!</span>
								@endif

								{{ $attendee->user->name }}
							</dd>
						@endforeach
					@endif
				</dt>
			</div>

			<div class="col-md-3 col-lg-3">
				<div class="visible-lg">
					@if ($currentUser != $event->staff->user)
						@if ( ! $currentUser->isAttending($appointment->id))
							<a href="#" class="btn btn-block btn-primary js-enroll" data-appointment="{{ $appointment->id }}">Enroll Now</a>
						@endif

						@if ($currentUser->isAttending($appointment->id))
							<a href="#" class="btn btn-block btn-danger js-withdraw" data-appointment="{{ $currentUser->getAppointment($appointment->id)->first()->id }}">Withdraw</a>
						@endif
					@endif

					@if ($currentUser->isStaff())
						<a href="#" class="btn btn-block btn-default">Manage</a>

						@if ($appointment->service->isOneToOne())
							<a href="#" class="btn btn-block btn-primary">Mark as Paid</a>
						@endif
					@endif
				</div>
				<div class="hidden-lg">
					@if ($currentUser != $event->staff->user)
						@if ( ! $currentUser->isAttending($appointment->id))
							<p><a href="#" class="btn btn-block btn-lg btn-primary js-enroll" data-appointment="{{ $appointment->id }}">Enroll Now</a></p>
						@endif

						@if ($currentUser->isAttending($appointment->id))
							<p><a href="#" class="btn btn-block btn-lg btn-danger js-withdraw" data-appointment="{{ $currentUser->getAppointment($appointment->id)->first()->id }}">Withdraw</a></p>
						@endif
					@endif

					@if ($currentUser->isStaff())
						<p><a href="#" class="btn btn-block btn-lg btn-default">Manage</a></p>

						@if ($appointment->service->isOneToOne())
							<a href="#" class="btn btn-block btn-lg btn-primary">Mark as Paid</a>
						@endif
					@endif
				</div>
			</div>
		@endif
	</div>
@endsection

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
@endsection