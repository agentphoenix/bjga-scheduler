@extends('layouts.master')

@section('title')
	Edit Appointment
@stop

@section('content')
	<h1>Edit Appointment</h1>

	<div class="visible-md visible-lg">
		<div class="btn-toolbar">
			@if ($_currentUser->access() > 1)
				<div class="btn-group">
					<a href="{{ URL::route('admin.user.index') }}" class="btn btn-sm btn-default icn-size-16">{{ $_icons['back'] }}</a>
				</div>
			@endif
		</div>
	</div>
	<div class="visible-xs visible-sm">
		<div class="row">
			@if ($_currentUser->access() > 1)
				<div class="col-xs-12 col-sm-3">
					<p><a href="{{ URL::route('admin.user.index') }}" class="btn btn-block btn-lg btn-default icn-size-16">{{ $_icons['back'] }}</a></p>
				</div>
			@endif
		</div>
	</div>

	{{ Form::model($appointment, array('route' => array('admin.appointment.update', $appointment->id), 'method' => 'put')) }}
		<div class="row">
			<div class="col-md-9 col-lg-8">
				<div class="form-group">
					<label class="control-label">Notes</label>
					{{ Form::textarea('notes', null, array('class' => 'form-control', 'rows' => 5)) }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-4 col-md-3 col-lg-2">
				<div class="form-group{{ ($errors->has('start')) ? ' has-error' : '' }}">
					<label class="control-label">Date</label>
					{{ Form::text('staff[date]', $appointment->start->format('Y-m-d'), array('class' => 'form-control js-datepicker')) }}
					{{ $errors->first('start', '<p class="help-block">:message</p>') }}
				</div>
			</div>
			<div class="col-sm-4 col-md-3 col-lg-2">
				<div class="form-group{{ ($errors->has('start')) ? ' has-error' : '' }}">
					<label class="control-label">Start Time</label>
					{{ Form::text('staff[start]', $appointment->start->format('H:i'), array('class' => 'form-control js-timepicker')) }}
					{{ $errors->first('start', '<p class="help-block">:message</p>') }}
				</div>
			</div>
			<div class="col-sm-4 col-md-3 col-lg-2">
				<div class="form-group{{ ($errors->has('end')) ? ' has-error' : '' }}">
					<label class="control-label">End Time</label>
					{{ Form::text('staff[end]', $appointment->end->format('H:i'), array('class' => 'form-control js-timepicker')) }}
					{{ $errors->first('end', '<p class="help-block">:message</p>') }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-4 col-md-3 col-lg-2">
				<div class="form-group">
					<label class="control-label">Total Due</label>
					<div class="input-group">
						<span class="input-group-addon"><strong>$</strong></span>
						{{ Form::text('user[amount]', $appointment->userAppointments->first()->amount, array('class' => 'form-control')) }}
					</div>
				</div>
			</div>
			<div class="col-sm-4 col-md-3 col-lg-4">
				<div class="form-group">
					<label class="control-label">Paid?</label>
					<div class="controls">
						<label class="radio-inline text-sm">{{ Form::radio('user[paid]', 1, ($appointment->userAppointments->first()->paid === 1)) }} Yes</label>
						<label class="radio-inline text-sm">{{ Form::radio('user[paid]', 0, ($appointment->userAppointments->first()->paid === 0)) }} No</label>
					</div>
				</div>
			</div>
		</div>

		{{ Form::hidden('staff_appointment_id', $appointment->id) }}
		{{ Form::hidden('user_appointment_id', $appointment->userAppointments->first()->id) }}

		<div class="row">
			<div class="col-lg-12">
				<div class="visible-md visible-lg">
					{{ Form::submit('Update', array('class' => 'btn btn-lg btn-primary')) }}
				</div>
				<div class="visible-xs visible-sm">
					{{ Form::submit('Update', array('class' => 'btn btn-lg btn-block btn-primary')) }}
				</div>
			</div>
		</div>
	{{ Form::close() }}
@stop

@section('styles')
	{{ HTML::style('css/picker.default.css') }}
	{{ HTML::style('css/picker.default.date.css') }}
	{{ HTML::style('css/picker.default.time.css') }}
@stop

@section('scripts')
	{{ HTML::script('js/picker.js') }}
	{{ HTML::script('js/picker.date.js') }}
	{{ HTML::script('js/picker.time.js') }}
	{{ HTML::script('js/picker.legacy.js') }}
	<script>
		$(function()
		{
			$('.js-datepicker').pickadate({
				format: "yyyy-mm-dd",
				max: false,
				container: '.container-fluid',
				editable: true
			});

			$('.js-timepicker').pickatime({
				format: "HH:i",
				interval: 15,
				min: [7, 0],
				max: [21, 0],
				container: '.container-fluid',
				editable: true
			});
		});
	</script>
@stop