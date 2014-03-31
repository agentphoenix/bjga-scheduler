@extends('layouts.master')

@section('title')
	Edit Recurring Lesson
@stop

@section('content')
	<h1>Edit Recurring Lesson</h1>

	<div class="visible-md visible-lg">
		<div class="btn-toolbar">
			<div class="btn-group">
				<a href="{{ URL::route('admin.appointment.recurring.index') }}" class="btn btn-sm btn-default icn-size-16">{{ $_icons['back'] }}</a>
			</div>
		</div>
	</div>
	<div class="visible-xs visible-sm">
		<div class="row">
			<div class="col-xs-6 col-sm-3">
				<p><a href="{{ URL::route('admin.appointment.recurring.index') }}" class="btn btn-lg btn-block btn-default icn-size-16">{{ $_icons['back'] }}</a></p>
			</div>
		</div>
	</div>

	<p><strong>Student:</strong> {{ $recurring->present()->userName }}</p>
	<p><strong>Service:</strong> {{ $recurring->present()->serviceName }}</p>

	<hr>

	<h3>Select New Date</h3>

	<p>The new date you select will be used for the next appointment in the series. All appointments beyond that will be shifted accordingly. <strong class="text-danger">This will not take instructor availability into account, so make sure you have verified the time is available.</strong></p>

	{{ Form::open(array('route' => array('admin.appointment.recurring.store', $recurring->id))) }}
		<div class="row">
			<div class="col-lg-3">
				<div class="form-group">
					{{ Form::text('newDate', null, array('class' => 'js-datepicker form-control')) }}
				</div>
			</div>
			<div class="col-lg-2">
				<div class="visible-md visible-lg">
					{{ Form::button('Change', array('type' => 'submit', 'class' => 'btn btn-sm btn-block btn-primary')) }}
				</div>
				<div class="visible-xs visible-sm">
					<p>{{ Form::button('Change', array('type' => 'submit', 'class' => 'btn btn-lg btn-block btn-primary')) }}</p>
				</div>
			</div>
		</div>
	{{ Form::close() }}

	<h3>Appointment Schedule</h3>

	<div class="row">
		<div class="col-lg-6">
			<div class="data-table data-table-striped data-table-bordered">
			@foreach ($recurring->present()->staffAppointments as $sa)
				<div class="row">
					<div class="col-sm-6 col-md-6 col-lg-7">
						@if ($sa->start < $today)
							<p class="text-muted">
						@else
							<p>
						@endif
						{{ $sa->present()->appointmentDate }}</p>
					</div>
					<div class="col-sm-6 col-md-6 col-lg-5">
						@if ($sa->start < $today)
							<p class="text-muted">
						@else
							<p>
						@endif
						{{ $sa->present()->appointmentTime }}</p>
					</div>
				</div>
			@endforeach
			</div>
		</div>
	</div>
@stop

@section('scripts')
	<script src="{{ URL::asset('js/moment.min.js') }}"></script>
	<script src="{{ URL::asset('js/bootstrap-datetimepicker.min.js') }}"></script>
	<script>
		
		$(function()
		{
			$('.js-datepicker').datetimepicker({
				format: "YYYY-MM-DD HH:mm",
				minuteStepping: 15,
				minDate: moment(),
				sideBySide: true
			});
		});

	</script>
@stop