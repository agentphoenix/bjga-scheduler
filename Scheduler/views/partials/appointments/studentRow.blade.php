<div class="row">
	<div class="col-sm-3 col-md-2 col-lg-2">
		<p class="text-sm"><strong>{{ $appt->start->format(Config::get('bjga.dates.time')) }} - {{ $appt->end->format(Config::get('bjga.dates.time')) }}</strong></p>
	</div>
	<div class="col-sm-9 col-md-5 col-lg-6">
		<p class="lead">
			<strong>{{ trim($appt->service->name) }}</strong>
		</p>
	</div>
	<div class="col-sm-12 col-md-5 col-lg-4">
		<div class="visible-md visible-lg">
			<div class="btn-toolbar pull-right">
				@if ($appt->service->isProgram())
					<div class="btn-group">
						<a href="{{ URL::route('event', array($appt->service->slug)) }}" class="btn btn-sm btn-default icn-size-16 js-tooltip-top" data-title="More Info">{{ $_icons['info'] }}</a>
					</div>
				@endif

				<div class="btn-group">
					<a href="#" class="btn btn-sm btn-default icn-size-16 js-email-instructor js-tooltip-top" data-appt="{{ $appt->id }}" data-title="Email Instructor">{{ $_icons['email'] }}</a>
				</div>

				@if ( ! $appt->hasStarted())
					<div class="btn-group">
						<a href="#" class="btn btn-sm btn-danger icn-size-16 js-withdraw js-tooltip-top" data-type="student" data-appointment="{{ $appt->id }}" data-title="Cancel Appointment">{{ $_icons['reject'] }}</a>
					</div>
				@endif
			</div>
		</div>
		<div class="visible-xs visible-sm">
			@if ($appt->service->isProgram())
				<p><a href="{{ URL::route('event', array($appt->service->slug)) }}" class="btn btn-lg btn-block btn-default icn-size-16">More Info</a></p>
			@endif

			<p><a href="#" class="btn btn-lg btn-block btn-default icn-size-16 js-email-instructor" data-appt="{{ $appt->id }}">Email Instructor</a></p>

			@if ( ! $appt->hasStarted())
				<p><a href="#" class="btn btn-lg btn-block btn-danger icn-size-16 js-withdraw" data-type="student" data-appointment="{{ $appt->id }}">Cancel Appointment</a></p>
			@endif
		</div>
	</div>
</div>