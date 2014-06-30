<div class="row">
	<div class="col-xs-4 col-sm-3 col-md-2 col-lg-2">
		<p class="lead"><strong>{{ $appt->start->format(Config::get('bjga.dates.time')) }}</strong></p>
	</div>
	<div class="col-xs-8 col-sm-9 col-md-5 col-lg-6">
		<p class="lead">
			<a href="#" class="no-color js-details" data-id="{{ $appt->id }}"><strong>
				@if ($appt->service->isLesson())
					{{ trim($appt->userAppointments->first()->user->name) }}
				@else
					{{ trim($appt->service->name) }}
				@endif
			</strong></a>
		</p>
	</div>
</div>