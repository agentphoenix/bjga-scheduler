@extends('layouts.master')

@section('title')
	Edit Recurring Lesson
@stop

@section('content')
	<h1>Edit Recurring Lesson</h1>

	<div class="visible-md visible-lg">
		<div class="btn-toolbar">
			<div class="btn-group">
				<a href="{{ route('admin.appointment.recurring.index') }}" class="btn btn-sm btn-default icn-size-16">{{ $_icons['back'] }}</a>
			</div>
		</div>
	</div>
	<div class="visible-xs visible-sm">
		<div class="row">
			<div class="col-xs-6 col-sm-3">
				<p><a href="{{ route('admin.appointment.recurring.index') }}" class="btn btn-lg btn-block btn-default icn-size-16">{{ $_icons['back'] }}</a></p>
			</div>
		</div>
	</div>

	<p><strong>Student:</strong> {{ $recurring->present()->userName }}</p>
	<p><strong>Service:</strong> {{ $recurring->present()->serviceName }}</p>

	<hr>

	<h3>Update Series</h3>

	<p>To update the series, select the effective appointment, the new date, and the new start time and click <strong>Change</strong>. All appointments in the series, beginning with the effective appointment you select, will be shifted to the new date/time for the remainder of the series. <strong class="text-danger">This will not take instructor availability into account, so make sure you have verified the time is available.</strong></p>

	{{ Form::open(array('route' => array('admin.appointment.recurring.update', $recurring->id))) }}
		<div class="row">
			<div class="col-md-4 col-lg-3">
				<div class="form-group">
					<label class="control-label">Effective Appointment</label>
					{{ Form::select('startingWith', $startingWith, null, ['class' => 'form-control']) }}
				</div>
			</div>
			<div class="col-md-2 col-lg-3">
				<div class="form-group">
					<label class="control-label">New Date</label>
					{{ Form::text('newDate', null, array('class' => 'js-datepicker form-control')) }}
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label">New Start Time</label>
					{{ Form::text('newTime', null, array('class' => 'js-timepicker form-control')) }}
				</div>
			</div>
			<div class="col-md-3 col-lg-2">
				<div class="visible-md visible-lg">
					<label class="control-label">&nbsp;</label>
					{{ Form::button('Change', array('type' => 'submit', 'class' => 'btn btn-sm btn-block btn-default')) }}
				</div>
				<div class="visible-xs visible-sm">
					<p>{{ Form::button('Change', array('type' => 'submit', 'class' => 'btn btn-lg btn-block btn-default')) }}</p>
				</div>
			</div>
		</div>
	{{ Form::close() }}

	@if ($_currentUser->access() == 4)
		<p class="visible-xs visible-sm"><a href="#" class="btn btn-danger btn-lg btn-block js-cancelRemaining" data-series="{{ $recurring->id }}">Cancel Remaining Lessons</a></p>
		<p class="visible-md visible-lg"><a href="#" class="btn btn-danger js-cancelRemaining" data-series="{{ $recurring->id }}">Cancel Remaining Lessons</a></p>
	@endif

	<h3>Series Schedule</h3>

	<div class="row">
		<div class="col-lg-9">
			<div class="data-table data-table-striped data-table-bordered">
			@foreach ($recurring->present()->staffAppointments as $sa)
				<div class="row">
					<div class="col-xs-6 col-md-5">
						@if ($sa->start < $today)
							<p class="text-muted">
						@else
							<p>
						@endif
						{{ $sa->present()->appointmentDate }}</p>
					</div>
					<div class="col-xs-6 col-md-3">
						@if ($sa->start < $today)
							<p class="text-muted">
						@else
							<p>
						@endif
						{{ $sa->present()->appointmentTime }}</p>
					</div>
					<div class="col-xs-12 col-md-4">
						<div class="visible-md visible-lg">
							<div class="btn-toolbar pull-right">
								@if ((bool) $sa->userAppointments->first()->paid === false)
									<div class="btn-group">
										<a href="#" class="btn btn-sm btn-primary icn-size-16 js-markAsPaid js-tooltip-top" data-appt="{{ $sa->userAppointments->first()->id }}" data-title="Mark as Paid">{{ $_icons['check'] }}</a>
									</div>
								@endif

								@if ( ! $sa->hasEnded())
									<div class="btn-group">
										<a href="{{ route('admin.appointment.edit', array($sa->id)) }}" class="btn btn-default btn-sm icn-size-16">{{ $_icons['edit'] }}</a>
									</div>

									<div class="btn-group">
										<a href="#" class="btn btn-danger btn-sm icn-size-16 js-withdraw" data-type="staff" data-appointment="{{ $sa->id }}">{{ $_icons['reject'] }}</a>
									</div>
								@endif
							</div>
						</div>
						<div class="visible-xs visible-sm">
							<div class="row">
								@if ( ! $sa->hasEnded())
									<div class="col-sm-4">
										<p><a href="{{ route('admin.appointment.edit', array($sa->id)) }}" class="btn btn-default btn-lg btn-block">Edit Appointment</a></p>
									</div>
									
									<div class="col-sm-4">
										<p><a href="#" class="btn btn-danger btn-lg btn-block js-withdraw" data-type="staff" data-appointment="{{ $sa->id }}">Cancel Appointment</a></p>
									</div>
								@endif

								@if ((bool) $sa->userAppointments->first()->paid === false)
									<div class="col-sm-4">
										<p><a href="#" class="btn btn-lg btn-block btn-primary js-markAsPaid js-tooltip-top" data-appt="{{ $sa->userAppointments->first()->id }}">Mark As Paid</a>
									</div>
								@endif
							</div>
						</div>
					</div>
				</div>
			@endforeach
			</div>
		</div>
	</div>
@stop

@section('modals')
	{{ modal(['id' => 'instructorCancel', 'header' => "Cancel Appointment"]) }}
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
	{{ View::make('partials.jsMarkAsPaid') }}
	<script>
		$(function()
		{
			$('.js-datepicker').pickadate({
				format: "yyyy-mm-dd",
				max: false,
				container: '.container-fluid'
			});

			$('.js-timepicker').pickatime({
				format: "HH:i",
				interval: 15,
				min: [6, 0],
				max: [22, 0],
				container: '.container-fluid'
			});
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

		$('.js-cancelRemaining').on('click', function(e)
		{
			e.preventDefault();

			var series = $(this).data('series');

			$.ajax({
				url: "{{ route('admin.appointment.cancelRemainingSeries') }}",
				type: "POST",
				dataType: "json",
				data: {
					series: $(this).data('series'),
					"_token": "{{ csrf_token() }}"
				},
				success: function (data)
				{
					window.location.assign("{{ route('admin.appointment.recurring.index') }}");
				}
			});
		});
	</script>
@stop