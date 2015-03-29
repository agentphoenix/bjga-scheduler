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

	{{ Form::model($appointment, ['route' => ['admin.appointment.update', $appointment->id], 'method' => 'put', 'class' => 'form-horizontal']) }}
		<div class="form-group{{ ($errors->has('start')) ? ' has-error' : '' }}">
			<label class="col-sm-2 control-label">Date</label>
			<div class="col-sm-3">
				{{ Form::text('staff[date]', $appointment->start->format('l, M d, Y'), ['class' => 'form-control js-datepicker']) }}
				{{ $errors->first('start', '<p class="help-block">:message</p>') }}
			</div>
		</div>

		<div class="form-group{{ ($errors->has('start')) ? ' has-error' : '' }}">
			<label class="col-sm-2 control-label">Start Time</label>
			<div class="col-sm-3">
				{{ Form::text('staff[start]', $appointment->start->format('g:i A'), ['class' => 'form-control js-timepicker-start']) }}
				{{ $errors->first('start', '<p class="help-block">:message</p>') }}
			</div>
		</div>

		<div class="form-group{{ ($errors->has('end')) ? ' has-error' : '' }}">
			<label class="col-sm-2 control-label">End Time</label>
			<div class="col-sm-3">
				{{ Form::text('staff[end]', $appointment->end->format('g:i A'), ['class' => 'form-control js-timepicker-end']) }}
				{{ $errors->first('end', '<p class="help-block">:message</p>') }}
			</div>
		</div>

		<div class="form-group{{ ($errors->has('location_id')) ? ' has-error' : '' }}">
			<label class="col-sm-2 control-label">Location</label>
			<div class="col-sm-5">
				{{ Form::select('staff[location_id]', $locations, $appointment->location_id, ['class' => 'form-control']) }}
				{{ $errors->first('location_id', '<p class="help-block">:message</p>') }}
				<p class="help-block text-danger"><strong>Warning:</strong> Only change the location of this appointment if you <em>know</em> you'll be at the specified location on the date shown above!</p>
			</div>
		</div>

		<div class="form-group">
			<label class="col-sm-2 control-label">Total Due</label>
			<div class="col-sm-3">
				<div class="input-group">
					<span class="input-group-addon"><strong>$</strong></span>
					{{ Form::text('user[amount]', $appointment->userAppointments->first()->amount, array('class' => 'form-control')) }}
				</div>
			</div>
		</div>

		<div class="form-group">
			<label class="col-sm-2 control-label">Paid?</label>
			<div class="col-sm-3">
				<div>
					<label class="radio-inline text-sm">{{ Form::radio('user[paid]', 1, ($appointment->userAppointments->first()->paid == 1)) }} Yes</label>
					<label class="radio-inline text-sm">{{ Form::radio('user[paid]', 0, ($appointment->userAppointments->first()->paid == 0)) }} No</label>
				</div>
			</div>
		</div>

		<div class="form-group">
			<label class="col-sm-2 control-label">Notes</label>
			<div class="col-sm-8">
				{{ Form::textarea('notes', null, array('class' => 'form-control', 'rows' => 5)) }}
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-3 col-sm-offset-2">
				{{ Form::submit('Update', array('class' => 'btn btn-lg btn-block btn-primary')) }}
			</div>
		</div>

		{{ Form::hidden('staff_appointment_id', $appointment->id) }}
		{{ Form::hidden('user_appointment_id', $appointment->userAppointments->first()->id) }}
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
	{{ HTML::script('js/moment.min.js') }}
	<script>
		$(function()
		{
			$('.js-datepicker').pickadate({
				format: "dddd, mmm dd, yyyy",
				formatSubmit: "yyyy-mm-dd",
				hiddenName: true,
				max: false,
				container: '.container-fluid',
				onSet: function(context)
				{
					var $day = moment(this.get(), "dddd, MMM D, YYYY");

					$.ajax({
						type: "GET",
						url: "{{ URL::to('ajax/staff') }}/{{ $service->staff_id }}",
						dataType: "json",
						success: function(data)
						{
							var dayOfWeek = $day.format("dddd");

							$('[name="staff[location_id]"]').val(data.schedule[dayOfWeek].locationId);
						}
					});
				}
			});

			var $end = $('.js-timepicker-end').pickatime({
				format: "h:i A",
				formatSubmit: "HH:i",
				hiddenName: true,
				interval: 15,
				min: [6, 0],
				max: [22, 0],
				container: '.container-fluid'
			});

			$('.js-timepicker-start').pickatime({
				format: "h:i A",
				formatSubmit: "HH:i",
				hiddenName: true,
				interval: 15,
				min: [6, 0],
				max: [22, 0],
				container: '.container-fluid',
				onSet: function(context)
				{
					var newEndTime = moment(this.get(), "h:mm A").add("{{ $service->duration }}", 'minute');

					$end.pickatime('picker').set('select', newEndTime.toDate());
				}
			});
		});
	</script>
@stop