@extends('layouts.master')

@section('title')
	My Schedule
@stop

@section('content')
	<h1>My Schedule</h1>

	@if ($_currentUser->isStaff())
		<div class="visible-xs visible-sm">
			@if (Request::segment(1))
				<p><a href="{{ route('home') }}" class="btn btn-lg btn-block btn-default">10 Days from Today</a></p>
			@endif

			@if (Request::segment(2) != "30")
				<p><a href="{{ route('homeDays', [30]) }}" class="btn btn-lg btn-block btn-default">30 Days from Today</a></p>
			@endif

			@if (Request::segment(2) != "90")
				<p><a href="{{ route('homeDays', [90]) }}" class="btn btn-lg btn-block btn-default">3 Months from Today</a></p>
			@endif

			@if (Request::segment(2) != "180")
				<p><a href="{{ route('homeDays', [180]) }}" class="btn btn-lg btn-block btn-default">6 Months from Today</a></p>
			@endif
		</div>
		<div class="visible-md visible-lg">
			<div class="btn-toolbar">
				@if (Request::segment(1))
					<div class="btn-group"><a href="{{ route('home') }}" class="btn btn-sm btn-default">10 Days from Today</a></div>
				@endif

				@if (Request::segment(2) != "30")
					<div class="btn-group"><a href="{{ route('homeDays', [30]) }}" class="btn btn-sm btn-default">30 Days from Today</a></div>
				@endif

				@if (Request::segment(2) != "90")
					<div class="btn-group"><a href="{{ route('homeDays', [90]) }}" class="btn btn-sm btn-default">3 Months from Today</a></div>
				@endif

				@if (Request::segment(2) != "180")
					<div class="btn-group"><a href="{{ route('homeDays', [180]) }}" class="btn btn-sm btn-default">6 Months from Today</a></div>
				@endif
			</div>
		</div>
	@endif

	@if (count($schedule) > 0)
		@foreach ($schedule as $days => $appointments)
			<?php $locationAppt = $appointments[0]->getStaffAppointment();?>

			<div class="row">
				<div class="col-sm-8">
					@if ($days === 0)
						<h2 class="no-margin">Today <small>{{ $now->format(Config::get('bjga.dates.dateNoDay')) }}</small></h2>
					@elseif ($days === 1)
						<h2 class="no-margin">Tomorrow <small>{{ $now->copy()->addDay()->format(Config::get('bjga.dates.dateNoDay')) }}</small></h2>
					@else
						<h2 class="no-margin">{{ $now->copy()->addDays($days)->format(Config::get('bjga.dates.day.long')) }} <small>{{ $now->copy()->addDays($days)->format(Config::get('bjga.dates.dateNoDay')) }}</small></h2>
					@endif
				</div>
				<div class="col-sm-4">
					@if ($_currentUser->isStaff() and (bool) $_currentUser->staff->instruction and $locationAppt->location)
						<div class="visible-xs visible-sm">
							<p><a class="btn btn-default btn-lg btn-block js-changeLocation" data-appointment="{{ $locationAppt->id }}">{{ $locationAppt->location->present()->name }}</a></p>
						</div>
						<div class="visible-md visible-lg pull-right">
							<a class="btn btn-default btn-sm js-changeLocation" data-appointment="{{ $locationAppt->id }}"><span class="icn-size-16">{{ $_icons['map'] }}</span>{{ $locationAppt->location->present()->name }}</a>
						</div>
					@endif
				</div>
			</div>

			<div class="data-table data-table-striped data-table-bordered">
			@foreach ($appointments as $a)
				<?php $appt = $a->getStaffAppointment();?>
				<?php $type = ($a instanceof Scheduler\Data\Models\Eloquent\UserAppointmentModel) ? 'user' : 'staff';?>

				@if ($type == 'user')
					{{ View::make('partials.appointments.studentRow')->withAppt($appt)->render() }}
				@else
					{{ View::make('partials.appointments.instructorRow')->withAppt($appt)->render() }}
				@endif
			@endforeach
			</div>
		@endforeach
	@else
		{{ partial('common/alert', array('content' => "No upcoming appointments.")) }}
		
		<div class="row">
			<div class="col-sm-6 col-md-4 col-lg-3">
				<p><a href="{{ route('book.lesson') }}" class="btn btn-lg btn-block btn-primary">Book a Lesson</a></p>
			</div>
			<div class="col-sm-6 col-md-4 col-lg-3">
				<p><a href="{{ route('book.program') }}" class="btn btn-lg btn-block btn-primary">Enroll in a Program</a></p>
			</div>
		</div>
	@endif

	<div style="clear:both;"></div>
@stop

@section('modals')
	{{ modal(['id' => 'sendEmail', 'header' => "Send Email"]) }}
	{{ modal(['id' => 'instructorCancel', 'header' => "Cancel Appointment"]) }}
	{{ modal(['id' => 'studentCancel', 'header' => "Cancel Appointment"]) }}
	{{ modal(['id' => 'attendees', 'header' => "Attendees"]) }}
	{{ modal(['id' => 'apptDetails', 'header' => 'Appointment Details']) }}
	{{ modal(['id' => 'changeLocation', 'header' => 'Change Location for This Day']) }}
	{{ modal(['id' => 'associateGoal', 'header' => 'Associate Lesson with Development Plan Goal']) }}
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

		$('.js-changeLocation').on('click', function(e)
		{
			e.preventDefault();

			var firstAppointment = $(this).data('appointment');

			$('#changeLocation').modal({
				remote: "{{ URL::to('ajax/change-location') }}/" + firstAppointment
			}).modal('show');
		});

		$('.js-goalAssociation').on('click', function(e)
		{
			e.preventDefault();

			var lesson = $(this).data('lesson');

			$('#associateGoal').modal({
				remote: "{{ URL::to('ajax/associate-goal') }}/" + lesson
			}).modal('show');
		});
	</script>
@stop