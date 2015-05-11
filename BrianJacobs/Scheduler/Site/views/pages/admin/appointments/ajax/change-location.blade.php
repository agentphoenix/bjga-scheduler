<p>This will change your scheduled location <strong>for this day only</strong>. If you need to move your entire schedule to another location for this day of the week moving forward, <a href="{{ route('admin.staff.schedule', [$user->staff->id]) }}">edit your regular availability</a> and change your location from there.</p>

<p class="alert alert-warning"><strong>Note:</strong> Changes to your location for a specific day will only affect private lessons and not programs. If you need to move a program to another location for a single day, please communicate that change to the attendees directly. If you need to move a program to another location for the entirety of the program, edit the program and change its location.</p>

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