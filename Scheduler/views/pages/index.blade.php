@extends('layouts.master')

@section('title')
	My Schedule
@stop

@section('content')
	<h1>My Schedule</h1>

	@if (count($schedule) > 0)
		@foreach ($schedule as $days => $appointments)
			@if ($days === 0)
				<h2>Today</h2>
			@elseif ($days === 1)
				<h2>Tomorrow</h2>
			@elseif ($days >= 2 and $days <= 6)
				<h2>{{ Date::now()->addDays($days)->format('l') }}</h2>
			@else
				<h2>{{ Date::now()->addDays($days)->format('l F jS, Y') }}</h2>
			@endif

			<div class="data-table data-table-striped data-table-bordered">
			@foreach ($appointments as $a)
				@if ($a instanceof Scheduler\UserAppointmentModel)
					<?php

					$appt = $a->appointment;
					$type = 'user';

					?>
				@else
					<?php

					$appt = $a;
					$type = 'staff';

					?>
				@endif

				<div class="row">
					<div class="col-lg-2">
						<p class="text-small"><strong>{{ $appt->start->format('g:ia') }} - {{ $appt->end->format('g:ia') }}</strong></p>
					</div>
					<div class="col-lg-6">
						<p class="lead">
							<strong>
								@if ($appt->service->isLesson())
									{{ trim($appt->userAppointments->first()->user->name) }} <span class="text-muted text-small">{{ trim($appt->service->name) }}</span>
								@else
									{{ trim($appt->service->name) }}
								@endif
							</strong>
						</p>
					</div>
					<div class="col-lg-4">
						<div class="visible-lg">
							<div class="btn-toolbar pull-right">
								@if ($appt->service->isProgram())
									<div class="btn-group">
										<a href="#" class="btn btn-sm btn-default icn-size-16">{{ $_icons['info'] }}</a>
									</div>
								@endif

								@if ($type == 'staff')
									@if ($appt->service->isLesson() or $appt->service->isProgram())
										<div class="btn-group">
											<a href="#" class="btn btn-sm btn-default icn-size-16">{{ $_icons['email'] }}</a>
										</div>
										
										<div class="btn-group">
											<a href="#" class="btn btn-sm btn-default icn-size-16">{{ $_icons['edit'] }}</a>
										</div>

										@if ($appt->userAppointments->count() === 1 and (bool) $appt->userAppointments->first()->paid === false)
											<div class="btn-group">
												<a href="#" class="btn btn-sm btn-primary icn-size-16">{{ $_icons['check'] }}</a>
											</div>
										@endif

										<div class="btn-group">
											<a href="#" class="btn btn-sm btn-danger icn-size-16 js-withdraw" data-appointment="{{ $appt->id }}">{{ $_icons['reject'] }}</a>
										</div>
									@else
										<div class="btn-group">
											<a href="{{ URL::route('admin.staff.block') }}" class="btn btn-sm btn-default icn-size-16">{{ $_icons['calendar'] }}</a>
										</div>
									@endif
								@endif
							</div>
						</div>
						<div class="hidden-lg">
							@if ($appt->service->isProgram())
								<p><a href="#" class="btn btn-block btn-lg btn-default icn-size-16">{{ $_icons['info'] }}</a></p>
							@endif

							<p><a href="#" class="btn btn-block btn-lg btn-danger icn-size-16 js-withdraw" data-appointment="{{ $appt->id }}">{{ $_icons['reject'] }}</a></p>
						</div>
					</div>
				</div>
			@endforeach
			</div>
		@endforeach
	@else
		{{ partial('common/alert', array('content' => "No upcoming appointments.")) }}
	@endif
@stop