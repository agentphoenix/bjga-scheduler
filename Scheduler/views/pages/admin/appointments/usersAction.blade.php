@extends('layouts.master')

@section('title')
	Appointments By Student
@stop

@section('content')
	<h1>Appointments <small>{{ $user->name }}</small></h1>

	<div class="visible-md visible-lg">
		<div class="btn-toolbar">
			<div class="btn-group">
				<a href="{{ URL::route('admin.appointment.user') }}" class="btn btn-sm btn-default icn-size-16">{{ $_icons['back'] }}</a>
			</div>
		</div>
	</div>
	<div class="visible-xs visible-sm">
		<div class="row">
			<div class="col-xs-6 col-sm-3">
				<p><a href="{{ URL::route('admin.appointment.user') }}" class="btn btn-lg btn-block btn-default icn-size-16">{{ $_icons['back'] }}</a></p>
			</div>
		</div>
	</div>

	@if (count($schedule) > 0)
		@foreach ($schedule as $days => $appointments)
			@if ($days === 0)
				<h2>Today</h2>
			@elseif ($days === 1)
				<h2>Tomorrow</h2>
			@elseif ($days >= 2 and $days <= 6)
				<h2>{{ Date::now()->addDays($days)->format(Config::get('bjga.dates.day.long')) }}</h2>
			@else
				<h2>{{ Date::now()->addDays($days)->format(Config::get('bjga.dates.date')) }}</h2>
			@endif

			<div class="data-table data-table-striped data-table-bordered">
			@foreach ($appointments as $a)
				<?php $appt = $a->appointment;?>

				@if ($appt->service->isLesson())
					<div class="row">
						<div class="col-sm-3 col-md-2 col-lg-2">
							<p class="text-sm"><strong>{{ $appt->start->format(Config::get('bjga.dates.time')) }} - {{ $appt->end->format(Config::get('bjga.dates.time')) }}</strong></p>
						</div>
						<div class="col-sm-9 col-md-5 col-lg-6">
							<p class="lead"><strong>{{ trim($appt->service->name) }}</strong></p>
						</div>
						<div class="col-sm-12 col-md-5 col-lg-4">
							<div class="visible-md visible-lg">
								<div class="btn-toolbar pull-right">
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

										<div class="btn-group">
											<a href="#" class="btn btn-sm btn-danger icn-size-16 js-withdraw" data-appointment="{{ $appt->id }}">{{ $_icons['reject'] }}</a>
										</div>
									@else
										<div class="btn-group">
											<a href="{{ URL::route('admin.staff.block') }}" class="btn btn-sm btn-default icn-size-16">{{ $_icons['calendar'] }}</a>
										</div>
									@endif
								</div>
							</div>
							<div class="visible-xs visible-sm">
								@if ($appt->service->isLesson() or $appt->service->isProgram())
									<p><a href="#" class="btn btn-lg btn-block btn-default icn-size-16 js-email" data-service="{{ $appt->service->id }}" data-appt="{{ $appt->id }}">{{ $_icons['email'] }}</a></p>
									
									<p>
										@if ($appt->service->isLesson())
											<a href="{{ URL::route('admin.appointment.edit', array($appt->id)) }}" class="btn btn-lg btn-block btn-default icn-size-16">{{ $_icons['edit'] }}</a>
										@else
											<a href="{{ URL::route('admin.service.edit', array($appt->service->id)) }}" class="btn btn-lg btn-block btn-default icn-size-16">{{ $_icons['edit'] }}</a>
										@endif
									</p>

									@if ($appt->service->isLesson())
										@if ((bool) $appt->userAppointments->first()->paid === false)
											<p><a href="#" class="btn btn-lg btn-block btn-primary icn-size-16 js-markAsPaid" data-appt="{{ $appt->userAppointments->first()->id }}">{{ $_icons['check'] }}</a></p>
										@endif
									@else
										<p><a href="#" class="btn btn-lg btn-block btn-default icn-size-16 js-attendees" data-id="{{ $appt->id }}">{{ $_icons['users'] }}</a></p>
									@endif

									<p><a href="#" class="btn btn-lg btn-block btn-danger icn-size-16 js-withdraw" data-appointment="{{ $appt->id }}">{{ $_icons['reject'] }}</a></p>
								@else
									<p><a href="{{ URL::route('admin.staff.block') }}" class="btn btn-lg btn-block btn-default icn-size-16">{{ $_icons['calendar'] }}</a></p>
								@endif
							</div>
						</div>
					</div>
				@else
					{{ partial('common/alert', array('class' => ' alert-info', 'content' => "{$user->name} is enrolled in {$appt->service->name} today.")) }}
				@endif
			@endforeach
			</div>
		@endforeach
	@else
		{{ partial('common/alert', array('content' => "No upcoming appointments for {$user->name}.")) }}
	@endif
@stop

@section('modals')
	{{ modal(array('id' => 'sendEmail', 'header' => "Send Email")) }}
	{{ modal(array('id' => 'instructorCancel', 'header' => "Cancel Appointment")) }}
@stop

@section('scripts')
	{{ View::make('partials.jsMarkAsPaid') }}
	<script>

		$('.js-email').on('click', function(e)
		{
			e.preventDefault();

			var service = $(this).data('service');
			var appt = $(this).data('appt');

			$('#sendEmail').modal({
				remote: "{{ URL::to('ajax/user/email/service') }}/" + service + "/appt/" + appt
			}).modal('show');
		});

		$('.js-withdraw').on('click', function(e)
		{
			e.preventDefault();

			var id = $(this).data('appointment');

			$('#instructorCancel').modal({
				remote: "{{ URL::to('ajax/cancel/staff') }}/" + id
			}).modal('show');
		});

	</script>
@stop