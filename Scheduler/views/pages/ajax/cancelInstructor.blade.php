<p class="alert alert-warning"><strong>Warning:</strong> Removing an attendee from a recurring lesson service or a program can create discrepancies in the revenue report. Do so only if necessary!</p>

<div class="row">
	<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
		<p><strong>Service</strong></p>
	</div>
	<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
		<p>{{ $appointment->service->name }}</p>
	</div>
</div>
<div class="row">
	<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
		<p><strong>Attendees</strong></p>
	</div>
	<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
		<p>
		@foreach ($appointment->userAppointments as $ua)
			<label class="label label-default">{{ $ua->user->name }}</label>
		@endforeach
		</p>
	</div>
</div>
<div class="row">
	<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
		<p><strong>Start</strong></p>
	</div>
	<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
		<p>{{ $appointment->start->format(Config::get('bjga.dates.full')) }}</p>
	</div>
</div>
<div class="row">
	<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
		<p><strong>End</strong></p>
	</div>
	<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
		<p>{{ $appointment->end->format(Config::get('bjga.dates.full')) }}</p>
	</div>
</div>

<hr>

{{ Form::open(array('route' => array('book.cancel'))) }}
	<div class="row">
		<div class="col-lg-12">
			<div class="form-group">
				<label class="control-label">Reason for Cancellation</label>
				{{ Form::textarea('reason', null, array('class' => 'form-control')) }}
			</div>
		</div>
	</div>

	{{ Form::hidden('appointment', $appointment->id) }}

	<div class="visible-md visible-lg">
		{{ Form::button("Cancel", array('type' => 'submit', 'class' => 'btn btn-lg btn-danger')) }}
	</div>
	<div class="visible-xs visible-sm">
		{{ Form::button("Cancel", array('type' => 'submit', 'class' => 'btn btn-lg btn-block btn-danger')) }}
	</div>
{{ Form::close() }}