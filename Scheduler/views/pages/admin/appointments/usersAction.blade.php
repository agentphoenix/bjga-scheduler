@extends('layouts.master')

@section('title')
	Appointments By Student
@stop

@section('content')
	<h1>Appointments <small>{{ $user->name }}</small></h1>

	<div class="visible-md visible-lg">
		<div class="btn-toolbar">
			<div class="btn-group">
				<a href="{{ URL::route('admin.appointment.user') }}" class="btn btn-sm btn-default icn-size-16">{{ $_icons['back'] }}</a>
			</div>
		</div>
	</div>
	<div class="visible-xs visible-sm">
		<div class="row">
			<div class="col-xs-6 col-sm-3">
				<p><a href="{{ URL::route('admin.appointment.user') }}" class="btn btn-lg btn-block btn-default icn-size-16">{{ $_icons['back'] }}</a></p>
			</div>
		</div>
	</div>

	@if (count($schedule) > 0)
		@foreach ($schedule as $days => $appointments)
			@if ($days === 0)
				<h2>Today <small>{{ Date::now()->format(Config::get('bjga.dates.dateNoDay')) }}</small></h2>
			@elseif ($days === 1)
				<h2>Tomorrow <small>{{ Date::now()->addDay()->format(Config::get('bjga.dates.dateNoDay')) }}</small></h2>
			@elseif ($days >= 2 and $days <= 6)
				<h2>{{ Date::now()->addDays($days)->format(Config::get('bjga.dates.day.long')) }} <small>{{ Date::now()->addDays($days)->format(Config::get('bjga.dates.dateNoDay')) }}</small></h2>
			@else
				<h2>{{ Date::now()->addDays($days)->format(Config::get('bjga.dates.date')) }}</h2>
			@endif

			<div class="data-table data-table-striped data-table-bordered">
			@foreach ($appointments as $a)
				<?php $appt = $a->appointment;?>

				@if ($appt->service->isLesson())
					{{ View::make('partials.appointments.instructorRow')->withAppt($appt) }}
				@else
					{{ partial('common/alert', array('class' => ' alert-info', 'content' => "{$user->name} is enrolled in {$appt->service->name} today.")) }}
				@endif
			@endforeach
			</div>
		@endforeach
	@else
		{{ partial('common/alert', array('content' => "No upcoming appointments for {$user->name}.")) }}
	@endif
@stop

@section('modals')
	{{ modal(array('id' => 'sendEmail', 'header' => "Send Email")) }}
	{{ modal(array('id' => 'instructorCancel', 'header' => "Cancel Appointment")) }}
@stop

@section('scripts')
	{{ View::make('partials.jsMarkAsPaid') }}
	<script>

		$('.js-email').on('click', function(e)
		{
			e.preventDefault();

			var service = $(this).data('service');
			var appt = $(this).data('appt');

			$('#sendEmail').modal({
				remote: "{{ URL::to('ajax/user/email/service') }}/" + service + "/appt/" + appt
			}).modal('show');
		});

		$('.js-withdraw').on('click', function(e)
		{
			e.preventDefault();

			var id = $(this).data('appointment');

			$('#instructorCancel').modal({
				remote: "{{ URL::to('ajax/cancel/staff') }}/" + id
			}).modal('show');
		});

	</script>
@stop