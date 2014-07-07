@extends('layouts.master')

@section('title')
	My Schedule
@stop

@section('content')
	<h1>My Schedule</h1>

	@if (count($schedule) > 0)
		@foreach ($schedule as $days => $appointments)
			@if ($days === 0)
				<h2>Today <small>{{ $now->format(Config::get('bjga.dates.dateNoDay')) }}</small></h2>
			@elseif ($days === 1)
				<h2>Tomorrow <small>{{ $now->copy()->addDay()->format(Config::get('bjga.dates.dateNoDay')) }}</small></h2>
			@else
				<h2>{{ $now->copy()->addDays($days)->format(Config::get('bjga.dates.day.long')) }} <small>{{ $now->copy()->addDays($days)->format(Config::get('bjga.dates.dateNoDay')) }}</small></h2>
			@endif

			<div class="data-table data-table-striped data-table-bordered">
			@foreach ($appointments as $a)
				@if ($a instanceof Scheduler\Models\Eloquent\UserAppointmentModel)
					<?php

					$appt = $a->appointment;
					$type = 'user';

					?>
				@else
					<?php

					$appt = $a;
					$type = 'staff';

					?>
				@endif

				@if ($type == 'user')
					{{ View::make('partials.appointments.studentRow')->withAppt($appt) }}
				@else
					{{ View::make('partials.appointments.instructorRow')->withAppt($appt) }}
				@endif
			@endforeach
			</div>
		@endforeach
	@else
		{{ partial('common/alert', array('content' => "No upcoming appointments.")) }}
		
		<div class="row">
			<div class="col-sm-6 col-md-4 col-lg-3">
				<p><a href="{{ URL::route('book.lesson') }}" class="btn btn-lg btn-block btn-primary">Book a Lesson</a></p>
			</div>
			<div class="col-sm-6 col-md-4 col-lg-3">
				<p><a href="{{ URL::route('book.program') }}" class="btn btn-lg btn-block btn-primary">Enroll in a Program</a></p>
			</div>
		</div>
	@endif
@stop

@section('modals')
	{{ modal(['id' => 'sendEmail', 'header' => "Send Email"]) }}
	{{ modal(['id' => 'instructorCancel', 'header' => "Cancel Appointment"]) }}
	{{ modal(['id' => 'studentCancel', 'header' => "Cancel Appointment"]) }}
	{{ modal(['id' => 'attendees', 'header' => "Attendees"]) }}
	{{ modal(['id' => 'apptDetails', 'header' => 'Appointment Details']) }}
@stop

@section('scripts')
	{{ View::make('partials.jsMarkAsPaid') }}
	<script>

		$('.js-attendees').on('click', function(e)
		{
			e.preventDefault();

			var id = $(this).data('id');

			$('#attendees').modal({
				remote: "{{ URL::to('admin/appointment/attendees/appointment') }}/" + id
			}).modal('show');
		});

		$('.js-details').on('click', function(e)
		{
			e.preventDefault();

			var id = $(this).data('id');

			$('#apptDetails').modal({
				remote: "{{ URL::to('admin/appointment/details') }}/" + id
			}).modal('show');
		});
		
		$('.js-email').on('click', function(e)
		{
			e.preventDefault();

			var service = $(this).data('service');
			var appt = $(this).data('appt');

			$('#sendEmail').modal({
				remote: "{{ URL::to('ajax/user/email/service') }}/" + service + "/appt/" + appt
			}).modal('show');
		});

		$('.js-email-instructor').on('click', function(e)
		{
			e.preventDefault();

			var appt = $(this).data('appt');

			$('#sendEmail').modal({
				remote: "{{ URL::to('ajax/user/email/instructor') }}/appt/" + appt
			}).modal('show');
		});

		$('.js-withdraw').on('click', function(e)
		{
			e.preventDefault();

			var id = $(this).data('appointment');
			var type = $(this).data('type');

			if (type == "staff")
			{
				$('#instructorCancel').modal({
					remote: "{{ URL::to('ajax/cancel/staff') }}/" + id
				}).modal('show');
			}
			else
			{
				$('#studentCancel').modal({
					remote: "{{ URL::to('ajax/cancel/student') }}/" + id
				}).modal('show');
			}
		});

	</script>
@stop