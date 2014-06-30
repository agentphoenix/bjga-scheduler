<div class="data-table data-table-bordered data-table-striped">
	<div class="row">
		<div class="col-xs-3">
			<p class="lead"><strong>Type</strong></p>
		</div>
		<div class="col-xs-9"><p>{{ $appt->service->name }}</p></div>
	</div>
	<div class="row">
		<div class="col-xs-3">
			<p class="lead"><strong>Start</strong></p>
		</div>
		<div class="col-xs-9"><p>{{ $appt->start->format(Config::get('bjga.dates.full')) }}</p></div>
	</div>
	<div class="row">
		<div class="col-xs-3">
			<p class="lead"><strong>End</strong></p>
		</div>
		<div class="col-xs-9"><p>{{ $appt->end->format(Config::get('bjga.dates.full')) }}</p></div>
	</div>

	@if ( ! empty($appt->notes))
		<div class="row">
			<div class="col-xs-3">
				<p class="lead"><strong>Notes</strong></p>
			</div>
			<div class="col-xs-9"><p>{{ $appt->notes }}</p></div>
		</div>
	@endif
</div>

@if ($appt->service->isProgram())
	<p><a href="{{ URL::route('event', array($appt->service->slug)) }}" class="btn btn-lg btn-block btn-default icn-size-16">More Info</a></p>
@endif

@if ($appt->service->isLesson() or $appt->service->isProgram())
	<p><a href="#" class="btn btn-lg btn-block btn-default icn-size-16 js-mobile-email" data-service="{{ $appt->service->id }}" data-appt="{{ $appt->id }}">Email Attendees</a></p>
	
	<p>
		@if ($appt->service->isLesson())
			<a href="{{ URL::route('admin.appointment.edit', array($appt->id)) }}" class="btn btn-lg btn-block btn-default icn-size-16">Edit Appointment</a>
		@else
			<a href="{{ URL::route('admin.service.edit', array($appt->service->id)) }}" class="btn btn-lg btn-block btn-default icn-size-16">Edit Service</a>
		@endif
	</p>

	@if ($appt->service->isRecurring())
		<p><a href="{{ URL::route('admin.appointment.recurring.edit', array($appt->recur_id)) }}" class="btn btn-lg btn-block btn-default icn-size-16">Edit Series</a></p>
	@endif

	@if ($appt->service->isLesson())
		@if ((bool) $appt->userAppointments->first()->paid === false)
			<p><a href="#" class="btn btn-lg btn-block btn-primary icn-size-16 js-markAsPaid" data-appt="{{ $appt->userAppointments->first()->id }}">Mark as Paid</a></p>
		@endif
	@else
		<p><a href="#" class="btn btn-lg btn-block btn-default icn-size-16 js-mobile-attendees" data-id="{{ $appt->id }}">See All Attendees</a></p>
	@endif

	@if ( ! $appt->hasStarted())
		<p><a href="#" class="btn btn-lg btn-block btn-danger icn-size-16 js-mobile-withdraw" data-type="staff" data-appointment="{{ $appt->id }}">Cancel Appointment</a></p>
	@endif
@else
	<p><a href="{{ URL::route('admin.staff.schedule', array($_currentUser->staff->id)) }}" class="btn btn-lg btn-block btn-default icn-size-16">View Schedule</a></p>
@endif

{{ View::make('partials.jsMarkAsPaid') }}
<script>

	$('.js-mobile-attendees').on('click', function(e)
	{
		e.preventDefault();

		var id = $(this).data('id');

		$('#apptDetails').modal('hide');

		$('#attendees').modal({
			remote: "{{ URL::to('admin/appointment/attendees/appointment') }}/" + id
		}).modal('show');
	});
	
	$('.js-mobile-email').on('click', function(e)
	{
		e.preventDefault();

		var service = $(this).data('service');
		var appt = $(this).data('appt');

		$('#apptDetails').modal('hide');

		$('#sendEmail').modal({
			remote: "{{ URL::to('ajax/user/email/service') }}/" + service + "/appt/" + appt
		}).modal('show');
	});

	$('.js-mobile-withdraw').on('click', function(e)
	{
		e.preventDefault();

		var id = $(this).data('appointment');
		var type = $(this).data('type');

		$('#apptDetails').modal('hide');

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