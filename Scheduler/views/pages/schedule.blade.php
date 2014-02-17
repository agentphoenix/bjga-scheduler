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
				@if ($a instanceof Scheduler\Models\Eloquent\UserAppointmentModel)
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
					<div class="col-md-2 col-lg-2">
						<p class="text-sm"><strong>{{ $appt->start->format('g:ia') }} - {{ $appt->end->format('g:ia') }}</strong></p>
					</div>
					<div class="col-md-6 col-lg-6">
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
					<div class="col-md-4 col-lg-4">
						<div class="visible-md visible-lg">
							<div class="btn-toolbar pull-right">
								@if ($appt->service->isProgram())
									<div class="btn-group">
										<a href="{{ URL::route('event', array($appt->service->slug)) }}" class="btn btn-sm btn-default icn-size-16">{{ $_icons['info'] }}</a>
									</div>
								@endif

								@if ($type == 'staff')
									@if ($appt->service->isLesson() or $appt->service->isProgram())
										<div class="btn-group">
											<a href="#" class="btn btn-sm btn-default icn-size-16">{{ $_icons['email'] }}</a>
										</div>
										
										<div class="btn-group">
											@if ($appt->service->isLesson())
												<a href="#" class="btn btn-sm btn-default icn-size-16">{{ $_icons['edit'] }}</a>
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
												<a href="#" class="btn btn-sm btn-default icn-size-16 js-markAsPaid">{{ $_icons['users'] }}</a>
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
								@else
									<div class="btn-group">
										<a href="#" class="btn btn-sm btn-danger icn-size-16 js-withdraw" data-appointment="{{ $appt->id }}">{{ $_icons['reject'] }}</a>
									</div>
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

@section('modals')
	{{ modal(array('id' => 'sendEmail', 'header' => "Send Email")) }}
@stop

@section('scripts')
	{{ View::make('partials.jsMarkAsPaid') }}
@stop