@extends('layouts.master')

@section('title')
	Student Appointment History
@stop

@section('content')
	<h1>Appointment History <small>{{ $user->name }}</small></h1>

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

	@if (count($history) > 0)
		<div class="data-table data-table-striped data-table-bordered">
		@foreach ($history as $days => $a)
			<?php $appt = $a->appointment;?>
			<div class="row">
				<div class="col-sm-4 col-md-4 col-lg-3">
					<p class="text-sm">
						<strong>{{ Date::createFromTimestamp($days)->format(Config::get('bjga.dates.date')) }}</strong><br>
						<span class="text-muted">{{ $appt->start->format(Config::get('bjga.dates.time')) }} - {{ $appt->end->format(Config::get('bjga.dates.time')) }}</span>
					</p>
				</div>
				<div class="col-sm-8 col-md-8 col-lg-9">
					<p class="lead"><strong>{{ trim($appt->service->name) }}</strong></p>
				</div>
			</div>
		@endforeach
		</div>
	@else
		{{ partial('common/alert', array('content' => "No upcoming appointments for {$user->name}.")) }}
	@endif
@stop

@section('modals')
	{{ modal(array('id' => 'sendEmail', 'header' => "Send Email")) }}
	{{ modal(array('id' => 'instructorCancel', 'header' => "Cancel Appointment")) }}
	{{ modal(array('id' => 'studentCancel', 'header' => "Cancel Appointment")) }}
	{{ modal(array('id' => 'attendees', 'header' => "Attendees")) }}
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