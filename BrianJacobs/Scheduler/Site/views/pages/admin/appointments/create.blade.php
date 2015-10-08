@extends('layouts.master')

@section('title')
	Add Appointment
@stop

@section('content')
	<h1>Add Appointment</h1>

	<div class="visible-md visible-lg">
		<div class="btn-toolbar">
			<div class="btn-group">
				<a href="{{ route('admin.appointment.index') }}" class="btn btn-sm btn-default icn-size-16">{{ $_icons['back'] }}</a>
			</div>
		</div>
	</div>
	<div class="visible-xs visible-sm">
		<div class="row">
			<div class="col-xs-6 col-sm-3">
				<p><a href="{{ route('admin.appointment.index') }}" class="btn btn-lg btn-block btn-default icn-size-16">{{ $_icons['back'] }}</a></p>
			</div>
		</div>
	</div>

	{{ partial('common/alert', array('class' => ' alert-warning', 'content' => "This page is for adding lesson appointments outside of normal availability. Make sure you have checked your schedule before adding an appointment through here! All other appointments should be booked through the normal booking page.")) }}

	{{ Form::open(array('route' => array('admin.appointment.store'))) }}
		<div class="row">
			<div class="col-lg-3">
				<div class="form-group">
					<label class="control-label">Student</label>
					<div class="controls">
						{{ Form::select('user', $users, null, array('class' => 'form-control')) }}
					</div>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-sm-8 col-md-6 col-lg-4">
				<div class="form-group">
					<label class="control-label">Service</label>
					<div class="controls">
						{{ Form::select('service_id', $services, null, array('class' => 'form-control')) }}
						<div id="serviceDescription"></div>
					</div>
				</div>
			</div>
			<div class="col-sm-4 col-md-6 col-lg-8">
				<div id="lessonServiceDetails"></div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-2">
				<label class="control-label">Price</label>
				<div class="input-group">
					<span class="input-group-addon"><strong>$</strong></span>
					{{ Form::text('price', null, array('class' => 'form-control')) }}
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<p class="help-block">If the service is free, enter zero.</p>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-2">
				<label class="control-label">Duration</label>
				{{ Form::text('duration', 60, array('class' => 'form-control')) }}
				<p class="help-block">Duration in minutes.</p>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-4 col-md-3 col-lg-2">
				<div class="form-group">
					<label class="control-label">Date</label>
					<div class="controls">
						{{ Form::text('date', null, array('class' => 'form-control js-datepicker')) }}
					</div>
				</div>
			</div>
			<div class="col-sm-4 col-md-3 col-lg-2">
				<div class="form-group">
					<label class="control-label">Start Time</label>
					<div class="controls">
						{{ Form::text('start', null, array('class' => 'form-control js-timepicker-start')) }}
					</div>
				</div>
			</div>
			<div class="col-sm-4 col-md-3 col-lg-2">
				<div class="form-group">
					<label class="control-label">End Time</label>
					<div class="controls">
						{{ Form::text('end', null, array('class' => 'form-control js-timepicker-end')) }}
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-2">
				<div class="form-group{{ ($errors->has('occurrences')) ? ' has-error' : '' }}">
					<label class="control-label">Occurrences</label>
					{{ Form::text('occurrences', null, array('class' => 'form-control input-with-feedback')) }}
					{{ $errors->first('occurrences', '<p class="help-block">:message</p>') }}
				</div>
			</div>
			<div class="col-lg-3">
				<div class="form-group">
					<label class="control-label">Occurrence Schedule</label>
					{{ Form::text('occurrences_schedule', null, array('class' => 'form-control input-with-feedback')) }}
					<p class="help-block">Days between occurrences.</p>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-6">
				<div class="form-group">
					<label class="control-label">Notes</label>
					<div class="controls">
						{{ Form::textarea('notes', false, array('class' => 'form-control', 'rows' => 5)) }}
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<div class="form-group">
					<div>
						<label class="checkbox-inline text-sm">
							{{ Form::checkbox('email_student', 1, true) }} Notify the student of the appointment
						</label>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<div class="visible-md visible-lg">
					<p>{{ Form::submit('Add Appointment', array('class' => 'btn btn-lg btn-primary')) }}</p>
				</div>
				<div class="visible-xs visible-sm">
					<p>{{ Form::submit('Add Appointment', array('class' => 'btn btn-block btn-lg btn-primary')) }}</p>
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
	{{ HTML::script('js/moment.min.js') }}
	<script>
		$('[name="service_id"]').on('change', function(e)
		{
			$.ajax({
				url: "{{ URL::to('ajax/service/getService') }}",
				data: { service: $('[name="service_id"] option:selected').val() },
				success: function(data)
				{
					var obj = $.parseJSON(data);
					var price = obj.service.price;
					var duration = obj.service.duration;
					var occurrences = obj.service.occurrences;
					var occurrences_schedule = obj.service.occurrences_schedule;
					//price = price.replace("$", "");

					$('[name="price"]').val(price);
					$('[name="duration"]').val(duration);
					$('[name="occurrences"]').val(occurrences);
					$('[name="occurrences_schedule"]').val(occurrences_schedule);
				}
			});
		});

		$(function()
		{
			$('.js-datepicker').pickadate({
				format: "yyyy-mm-dd",
				min: 1,
				max: false,
				container: '.container-fluid',
				today: false
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
					var duration = $('[name="duration"]').val();

					var newEndTime = moment(this.get(), "h:mm A").add(duration, 'minute');

					$end.pickatime('picker').set('select', newEndTime.toDate());
				}
			});
		});
	</script>
@stop