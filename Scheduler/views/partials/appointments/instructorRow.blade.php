<div class="row">
	<div class="col-sm-3 col-md-2 col-lg-2">
		<p class="text-sm"><strong>{{ $appt->start->format(Config::get('bjga.dates.time')) }} - {{ $appt->end->format(Config::get('bjga.dates.time')) }}</strong></p>
	</div>
	<div class="col-sm-9 col-md-5 col-lg-6">
		<p class="lead">
			<strong>
				@if ($appt->service->isLesson())
					{{ trim($appt->userAppointments->first()->user->name) }} <span class="text-muted text-sm">{{ trim($appt->service->name) }}</span>
				@else
					{{ trim($appt->service->name) }}
				@endif
			</strong>
		</p>
	</div>
	<div class="col-sm-12 col-md-5 col-lg-4">
		<div class="visible-md visible-lg">
			<div class="btn-toolbar pull-right">
				@if ($appt->service->isProgram())
					<div class="btn-group">
						<a href="{{ URL::route('event', array($appt->service->slug)) }}" class="btn btn-sm btn-default icn-size-16">{{ $_icons['info'] }}</a>
					</div>
				@endif

				@if ($appt->service->isLesson() or $appt->service->isProgram())
					<div class="btn-group">
						<a href="#" class="btn btn-sm btn-default icn-size-16 js-email" data-service="{{ $appt->service->id }}" data-appt="{{ $appt->id }}">{{ $_icons['email'] }}</a>
					</div>
					
					<div class="btn-group">
						@if ($appt->service->isLesson())
							<a href="{{ URL::route('admin.appointment.edit', array($appt->id)) }}" class="btn btn-sm btn-default icn-size-16">{{ $_icons['edit'] }}</a>
						@else
							<a href="{{ URL::route('admin.service.edit', array($appt->service->id)) }}" class="btn btn-sm btn-default icn-size-16">{{ $_icons['edit'] }}</a>
						@endif
					</div>

					@if ($appt->service->isLesson())
						@if ((bool) $appt->userAppointments->first()->paid === false)
							<div class="btn-group">
								<a href="#" class="btn btn-sm btn-primary icn-size-16 js-markAsPaid" data-appt="{{ $appt->userAppointments->first()->id }}">{{ $_icons['check'] }}</a>
							</div>
						@endif
					@else
						<div class="btn-group">
							<a href="#" class="btn btn-sm btn-default icn-size-16 js-attendees" data-id="{{ $appt->id }}">{{ $_icons['users'] }}</a>
						</div>
					@endif

					@if ( ! $appt->hasStarted())
						<div class="btn-group">
							<a href="#" class="btn btn-sm btn-danger icn-size-16 js-withdraw" data-type="staff" data-appointment="{{ $appt->id }}">{{ $_icons['reject'] }}</a>
						</div>
					@endif
				@else
					<div class="btn-group">
						<a href="{{ URL::route('admin.staff.schedule', array($_currentUser->staff->id)) }}" class="btn btn-sm btn-default icn-size-16">{{ $_icons['calendar'] }}</a>
					</div>
				@endif
			</div>
		</div>
		<div class="visible-xs visible-sm">
			@if ($appt->service->isProgram())
				<p><a href="{{ URL::route('event', array($appt->service->slug)) }}" class="btn btn-lg btn-block btn-default icn-size-16">More Info</a></p>
			@endif

			@if ($appt->service->isLesson() or $appt->service->isProgram())
				<p><a href="#" class="btn btn-lg btn-block btn-default icn-size-16 js-email" data-service="{{ $appt->service->id }}" data-appt="{{ $appt->id }}">Email Attendees</a></p>
				
				<p>
					@if ($appt->service->isLesson())
						@if ( ! $appt->hasStarted())
							<a href="{{ URL::route('admin.appointment.edit', array($appt->id)) }}" class="btn btn-lg btn-block btn-default icn-size-16">Edit Appointment</a>
						@endif
					@else
						<a href="{{ URL::route('admin.service.edit', array($appt->service->id)) }}" class="btn btn-lg btn-block btn-default icn-size-16">Edit Service</a>
					@endif
				</p>

				@if ($appt->service->isLesson())
					@if ((bool) $appt->userAppointments->first()->paid === false)
						<p><a href="#" class="btn btn-lg btn-block btn-primary icn-size-16 js-markAsPaid" data-appt="{{ $appt->userAppointments->first()->id }}">Mark as Paid</a></p>
					@endif
				@else
					<p><a href="#" class="btn btn-lg btn-block btn-default icn-size-16 js-attendees" data-id="{{ $appt->id }}">See All Attendees</a></p>
				@endif

				@if ( ! $appt->hasStarted())
					<p><a href="#" class="btn btn-lg btn-block btn-danger icn-size-16 js-withdraw" data-type="staff" data-appointment="{{ $appt->id }}">Cancel Appointment</a></p>
				@endif
			@else
				<p><a href="{{ URL::route('admin.staff.schedule', array($_currentUser->staff->id)) }}" class="btn btn-lg btn-block btn-default icn-size-16">View Schedule</a></p>
			@endif
		</div>
	</div>
</div>