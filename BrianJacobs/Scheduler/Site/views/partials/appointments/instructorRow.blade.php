<div class="row">
	<div class="col-xs-4 col-sm-3 col-md-2 col-lg-2">
		<p class="lead visible-xs visible-sm{{ ($appt->hasEnded()) ? ' text-muted' : '' }}">
			<strong>{{ $appt->start->format(Config::get('bjga.dates.time')) }}</strong>
		</p>
		<p class="lead visible-md visible-lg{{ ($appt->hasEnded()) ? ' text-muted' : '' }}">
			{{ $appt->start->format(Config::get('bjga.dates.time')) }}
		</p>
	</div>
	<div class="col-xs-8 col-sm-9 col-md-5 col-lg-6">
		<p class="lead visible-xs visible-sm{{ ($appt->hasEnded()) ? ' text-muted' : '' }}">
			<strong><a href="#" class="no-color js-details" data-id="{{ $appt->id }}">
				@if ($appt->service->isLesson())
					{{ trim($appt->userAppointments->first()->user->name) }}
				@else
					{{ trim($appt->service->name) }}
				@endif
			</a></strong>
		</p>

		<p class="lead visible-md visible-lg{{ ($appt->hasEnded()) ? ' text-muted' : '' }}">
			@if ($appt->service->isLesson())
				{{ trim($appt->userAppointments->first()->user->name) }} <span class="text-muted text-sm">{{ trim($appt->service->name) }}</span>
			@else
				{{ trim($appt->service->name) }}
			@endif
		</p>
		@if ( ! empty($appt->notes))
			<span class="text-sm text-info">{{ $appt->present()->notes }}</span>
		@endif
	</div>
	<div class="col-md-5 col-lg-4">
		<div class="visible-md visible-lg">
			<div class="btn-toolbar pull-right">
				@if ($appt->service->isProgram())
					<div class="btn-group">
						<a href="{{ route('event', array($appt->service->slug)) }}" class="btn btn-sm btn-default icn-size-16 js-tooltip-top" data-title="More Info">{{ $_icons['info'] }}</a>
					</div>
				@endif

				@if ($appt->service->isLesson() or $appt->service->isProgram())
					@if ($appt->userAppointments->count() > 0)
						<div class="btn-group">
							<a href="#" class="btn btn-sm btn-default icn-size-16 js-email js-tooltip-top" data-service="{{ $appt->service->id }}" data-appt="{{ $appt->id }}" data-title="Email Attendees">{{ $_icons['email'] }}</a>
						</div>
					@endif
					
					<div class="btn-group">
						@if ($appt->service->isLesson())
							@if ($appt->service->isRecurring())
								<a href="{{ route('admin.appointment.recurring.edit', array($appt->recur_id)) }}" class="btn btn-sm btn-default icn-size-16 js-tooltip-top" data-title="Edit Series">{{ $_icons['edit'] }}</a>
							@else
								<a href="{{ route('admin.appointment.edit', array($appt->id)) }}" class="btn btn-sm btn-default icn-size-16 js-tooltip-top" data-title="Edit Appointment">{{ $_icons['edit'] }}</a>
							@endif
						@else
							<a href="{{ route('admin.service.edit', array($appt->service->id)) }}" class="btn btn-sm btn-default icn-size-16 js-tooltip-top" data-title="Edit Service">{{ $_icons['edit'] }}</a>
						@endif
					</div>

					@if ($appt->service->isLesson())
						@if ((bool) $appt->userAppointments->first()->paid === false)
							<div class="btn-group">
								<a href="#" class="btn btn-sm btn-primary icn-size-16 js-markAsPaid js-tooltip-top" data-appt="{{ $appt->userAppointments->first()->id }}" data-title="Mark as Paid">{{ $_icons['check'] }}</a>
							</div>
						@endif
					@else
						<div class="btn-group">
							<a href="#" class="btn btn-sm btn-default icn-size-16 js-attendees js-tooltip-top" data-id="{{ $appt->id }}" data-title="See All Attendees">{{ $_icons['users'] }}</a>
						</div>
					@endif

					@if ($appt->service->isLesson() and ! $appt->service->isRecurring())
						<div class="btn-group">
							<a href="#" class="btn btn-sm btn-danger icn-size-16 js-withdraw js-tooltip-top" data-type="staff" data-appointment="{{ $appt->id }}" data-title="Cancel Appointment">{{ $_icons['reject'] }}</a>
						</div>
					@endif
				@else
					<div class="btn-group">
						<a href="{{ route('admin.staff.schedule', array($_currentUser->staff->id)) }}" class="btn btn-sm btn-default icn-size-16 js-tooltip-top" data-title="View Schedule">{{ $_icons['calendar'] }}</a>
					</div>
				@endif
			</div>
		</div>
	</div>
</div>