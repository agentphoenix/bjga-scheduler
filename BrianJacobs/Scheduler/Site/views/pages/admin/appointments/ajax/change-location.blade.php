{{ Form::open(['route' => 'admin.appointment.changeLocation', 'method' => 'put', 'class' => 'form-horizontal']) }}
	<div class="form-group">
		<label class="control-label col-md-4">Current Location</label>
		<div class="col-md-8">
			<p class="form-control-static">{{ $appt->location->present()->name }}</p>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-md-4">New Location</label>
		<div class="col-md-8">
			{{ Form::select('new_location', $locations, null, ['class' => 'form-control input-lg']) }}
		</div>
	</div>

	{{ Form::hidden('firstAppointment', $appt->id) }}

	<div class="form-group">
		<div class="col-md-8 col-md-offset-4">
			<div class="visible-xs visible-sm">
				{{ Form::button('Change Location', ['type' => 'submit', 'class' => 'btn btn-primary btn-lg btn-block']) }}
			</div>
			<div class="visible-md visible-lg">
				{{ Form::button('Change Location', ['type' => 'submit', 'class' => 'btn btn-primary btn-lg']) }}
			</div>
		</div>
	</div>
{{ Form::close() }}