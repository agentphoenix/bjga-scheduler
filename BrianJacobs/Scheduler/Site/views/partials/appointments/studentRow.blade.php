<?php $userAppt = $appt->userAppointment($_currentUser);?>
<div class="row">
	<div class="col-xs-4 col-sm-3 col-md-2 col-lg-2">
		<p class="lead visible-xs visible-sm"><strong>{{ $appt->start->format(Config::get('bjga.dates.time')) }}</strong></p>
		<p class="lead visible-md visible-lg">{{ $appt->start->format(Config::get('bjga.dates.time')) }}</p>
	</div>
	<div class="col-xs-8 col-sm-9 col-md-5 col-lg-6">
		<p class="lead visible-xs visible-sm"><strong>{{ $appt->service->present()->name }}</strong></p>
		<p class="lead visible-md visible-lg">{{ $appt->service->present()->name }}</p>

		<p>Location: <strong class="text-success">{{ $userAppt->present()->location }}</strong></p>

		@if ($userAppt->paid == 0)
			<p>Total Due: <strong class="text-success">{{ $userAppt->present()->due }}</strong></p>
		@endif

		@if ($appt->goal)
			<p class="text-success">Part of your <strong><em>{{ link_to_route('goal.show', $appt->goal->present()->title, [$_currentUser->id, $appt->goal->id]) }}</em></strong> development plan goal</p>
		@endif
	</div>
	<div class="col-xs-12 col-sm-12 col-md-5 col-lg-4">
		<div class="visible-md visible-lg">
			<div class="btn-toolbar pull-right">
				@if ($appt->service->isProgram())
					<div class="btn-group">
						<a href="{{ route('event', array($appt->service->slug)) }}" class="btn btn-sm btn-default icn-size-16 js-tooltip-top" data-title="More Info">{{ $_icons['info'] }}</a>
					</div>
				@endif

				@if ($appt->service->isLesson())
					@if ($_currentUser->plan->activeGoals->count() > 0 and ! $appt->goal)
						<div class="btn-group">
							<a href="#" class="btn btn-sm btn-default icn-size-16 js-tooltip-top js-goalAssociation" data-lesson="{{ $appt->id }}" data-title="Associate with Goal">{{ $_icons['link'] }}</a>
						</div>
					@endif
				@endif

				<div class="btn-group">
					<a href="#" class="btn btn-sm btn-default icn-size-16 js-email-instructor js-tooltip-top" data-appt="{{ $appt->id }}" data-title="Email Instructor">{{ $_icons['email'] }}</a>
				</div>

				@if ( ! $userAppt->hasStarted())
					<div class="btn-group">
						<a href="#" class="btn btn-sm btn-danger icn-size-16 js-withdraw js-tooltip-top" data-type="student" data-appointment="{{ $appt->id }}" data-title="Cancel Appointment">{{ $_icons['reject'] }}</a>
					</div>
				@endif
			</div>
		</div>
		<div class="visible-xs visible-sm">
			@if ($appt->service->isProgram())
				<p><a href="{{ route('event', array($appt->service->slug)) }}" class="btn btn-lg btn-block btn-default icn-size-16">More Info</a></p>
			@endif

			@if ($appt->service->isLesson())
				@if ($_currentUser->plan->activeGoals->count() > 0 and ! $appt->goal)
					<p><a href="#" class="btn btn-lg btn-default btn-block js-goalAssociation" data-lesson="{{ $appt->id }}">Associate with Goal</a></p>
				@endif
			@endif

			<p><a href="#" class="btn btn-lg btn-block btn-default icn-size-16 js-email-instructor" data-appt="{{ $appt->id }}">Email Instructor</a></p>

			@if ( ! $userAppt->hasStarted())
				<p><a href="#" class="btn btn-lg btn-block btn-danger icn-size-16 js-withdraw" data-type="student" data-appointment="{{ $appt->id }}">Cancel Appointment</a></p>
			@endif
		</div>
	</div>
</div>