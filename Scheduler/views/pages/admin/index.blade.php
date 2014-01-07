@extends('layouts.master')

@section('title')
	Home
@endsection

@section('content')
	<div class="row">
		<div class="col-md-6 col-lg-6">
			<h1>Today's Schedule</h1>

			@if ($appointments->count() > 0)
				<dl>
				@foreach ($appointments as $appt)
					<dt>{{ $appt->service->name }}</dt>
					<dd><span class="label label-default">{{ Date::createFromFormat('H:i:s', $appt->start_time)->format('g:ia') }} - {{ Date::createFromFormat('H:i:s', $appt->end_time)->format('g:ia') }}</span></dd>

					@if ($appt->attendees->count() > 1)
						<dd>{{ $appt->attendees->count() }} attendees</dd>
					@else
						<dd>{{ $appt->attendees->first()->user->name }}</dd>
					@endif

					<dd>
						<div class="visible-lg">
							<div class="btn-toolbar">
								<div class="btn-group">
									<a href="#" class="btn btn-sm btn-default">Email</a>
									<a href="#" class="btn btn-sm btn-default">Edit</a>
								</div>

								@if ($appt->attendees->count() === 1 and (bool) $appt->attendees->first()->paid === false)
									<div class="btn-group">
										<a href="#" class="btn btn-sm btn-default">Mark as Paid</a>
									</div>
								@endif

								<div class="btn-group">
									<a href="#" class="btn btn-sm btn-danger">Cancel</a>
								</div>
							</div>
						</div>
						<div class="hidden-lg">
							<div class="row">
								<div class="col-sm-12">
									<p><a data-toggle="modal" href="#sendMessageModal" class="btn btn-block btn-lg btn-default">Send a Message</a></p>
								</div>
								<div class="col-sm-12">
									<p><a href="#" class="btn btn-block btn-lg btn-default">Edit Appointment</a></p>
								</div>

								@if ($appt->attendees->count() === 1 and (bool) $appt->attendees->first()->paid === false)
									<div class="col-sm-12">
										<p><a href="#" class="btn btn-block btn-lg btn-warning">Mark as Paid</a></p>
									</div>
								@endif

								<div class="col-sm-12">
									<p><a data-toggle="modal" href="#cancelModal" class="btn btn-block btn-lg btn-danger">Cancel Appointment</a></p>
								</div>
							</div>
						</div>
					</dd>
				@endforeach
				</dl>
			@else
				<div class="alert alert-warning">No appointments today.</div>
			@endif
		</div>

		<div class="col-md-6 col-lg-6">
			<h1>My Account</h1>

			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
					<p><a href="{{ URL::route('admin.user.edit', array($_currentUser->id)) }}" class="btn btn-lg btn-block btn-default">Edit My Account</a></p>
				</div>

				@if ($_currentUser->isStaff())
					<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
						<p><a href="{{ URL::route('admin.staff.edit', array($_currentUser->staff->id)) }}" class="btn btn-lg btn-block btn-default">Edit My Staff Account</a></p>
					</div>
				@endif
			</div>

			@if ($_currentUser->isStaff() and $_currentUser->access() > 1)
				<h1>Manage</h1>

				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<p><a href="{{ URL::route('admin.service.index') }}" class="btn btn-lg btn-block btn-primary">Manage Appointments</a></p>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
						<p><a href="#" class="btn btn-lg btn-block btn-default">View Reports</a></p>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
						<p><a href="{{ URL::route('admin.service.index') }}" class="btn btn-lg btn-block btn-default">Manage Services</a></p>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
						<p><a href="{{ URL::route('admin.user.index') }}" class="btn btn-lg btn-block btn-default">Manage Users</a></p>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
						<p><a href="{{ URL::route('admin.staff.index') }}" class="btn btn-lg btn-block btn-default">Manage Staff</a></p>
					</div>
				</div>
			@endif
		</div>
	</div>

	<div class="modal fade" id="cancelModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Cancel Appointment</h4>
				</div>
				<div class="modal-body">
					<p>Enter a message to send to the attendee(s):</p>

					{{ Form::textarea('message', null, array('class' => 'form-control')) }}
				</div>
				<div class="modal-footer">
					<div class="visible-lg">
						<button type="button" class="btn btn-danger">Cancel Appointment</button>
					</div>
					<div class="hidden-lg">
						<button type="button" class="btn btn-block btn-danger">Cancel Appointment</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="sendMessageModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Send Message</h4>
				</div>
				<div class="modal-body">
					<p>Enter a message to send to the attendee(s):</p>

					{{ Form::textarea('message', null, array('class' => 'form-control')) }}
				</div>
				<div class="modal-footer">
					<div class="visible-lg">
						<button type="button" class="btn btn-primary">Send Message</button>
					</div>
					<div class="hidden-lg">
						<button type="button" class="btn btn-block btn-primary">Send Message</button>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection