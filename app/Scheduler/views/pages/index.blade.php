@extends('layouts.master')

@section('title')
	Home
@endsection

@section('content')
	<div class="row">
		<div class="col-md-6 col-lg-6">
			@if (Auth::check())
				<h1>My Appointments</h1>

				<div class="row">
					<div class="col-md-12 col-lg-12">
						<div class="visible-lg">
							<p><a href="{{ URL::route('book.index') }}" class="btn btn-primary">Book Now</a></p>
						</div>
						<div class="hidden-lg">
							<p><a href="{{ URL::route('book.index') }}" class="btn btn-block btn-lg btn-primary">Book Now</a></p>
						</div>
					</div>
				</div>

				@if (count($myEvents) > 0)
					<dl>
						@foreach ($myEvents as $mine)
							<dt>{{ $mine->appointment->service->name }}</dt>
							<dd>
								<?php $apptDate = Date::createFromFormat('Y-m-d', $mine->appointment->date);?>
								<?php $apptTime = Date::createFromFormat('H:i:s', $mine->appointment->start_time);?>

								@if ($apptDate->isToday())
									Today
								@elseif ($apptDate->isTomorrow())
									Tommorow
								@else
									{{ $apptDate->format('l F jS, Y') }}
								@endif

								at {{ $apptTime->format('g:ia') }}
							</dd>
							<dd>
								<div class="visible-lg">
									<div class="btn-toolbar">
										<div class="btn-group">
											<a href="#" class="btn btn-sm btn-default">More Info</a>
										</div>
										<div class="btn-group">
											<a href="#" class="btn btn-sm btn-danger js-withdraw" data-appointment="{{ $mine->id }}">
												@if ($mine->appointment->service->isOneToOne())
													Cancel
												@else
													Withdraw
												@endif
											</a>
										</div>
									</div>
								</div>
								<div class="hidden-lg">
									<p><a href="#" class="btn btn-block btn-lg btn-default">More Info</a></p>

									<p><a href="#" class="btn btn-block btn-lg btn-danger js-withdraw" data-appointment="{{ $mine->id }}">
										@if ($mine->appointment->service->isOneToOne())
											Cancel
										@else
											Withdraw
										@endif
									</a></p>
								</div>
							</dd>
						@endforeach
					</dl>
				@else
					<div class="alert alert-warning">You don't have any upcoming events.</div>
				@endif
			@else
				<h1>Log In</h1>

				@if (Session::has('loginMessage'))
					<div class="alert alert-danger">{{ Session::get('loginMessage') }}</div>
				@endif

				{{ Form::open(array('url' => 'login')) }}
					<div class="row">
						<div class="col-md-10 col-lg-10">
							<div class="form-group{{ ($errors->has('email')) ? ' has-error' : '' }}">
								<label class="control-label">Email Address</label>
								{{ Form::email('email', null, array('class' => 'form-control')) }}
								{{ $errors->first('email', '<p class="help-block">:message</p>') }}
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-10 col-lg-10">
							<div class="form-group{{ ($errors->has('password')) ? ' has-error' : '' }}">
								<label class="control-label">Password</label>
								{{ Form::password('password', array('class' => 'form-control')) }}
								{{ $errors->first('password', '<p class="help-block">:message</p>') }}
							</div>
						</div>
					</div>

					<div class="visible-lg">
						{{ Form::button("Log In", array('type' => 'submit', 'class' => 'btn btn-primary')) }}
						<a href="{{ URL::route('register') }}" class="btn btn-default">Register</a>
					</div>
					<div class="hidden-lg">
						{{ Form::button("Log In", array('type' => 'submit', 'class' => 'btn btn-block btn-lg btn-primary')) }}
						<a href="{{ URL::route('register') }}" class="btn btn-block btn-lg btn-default">Register</a>
					</div>
				{{ Form::close() }}
			@endif
		</div>

		<div class="col-md-6 col-lg-6">
			<h1>Upcoming Events</h1>

			@if (count($upcomingEvents) > 0)
				<?php $eventCount = 0;?>
				<dl>
				@foreach ($upcomingEvents as $upcoming)
					@if ($eventCount < 5)
						<?php $eventDate = Date::createFromFormat('Y-m-d', $upcoming->date);?>
						<?php $eventStart = Date::createFromFormat('H:i:s', $upcoming->start_time);?>
						<?php $eventEnd = Date::createFromFormat('H:i:s', $upcoming->end_time);?>
						<?php $openSlots = $upcoming->service->user_limit - $upcoming->attendees->count();?>
						<?php $hasOpenings = $upcoming->attendees->count() < $upcoming->service->user_limit;?>

						<dt>{{ $upcoming->service->name }}</dt>
						<dd>
							@if ($eventDate->isToday())
								Today;
							@elseif ($eventDate->isTomorrow())
								Tommorow;
							@else
								{{ $eventDate->format('l F jS, Y') }};
							@endif

							{{ $eventStart->format('g:ia') }} - {{ $eventEnd->format('g:ia') }}
						</dd>
						
						<dd>
							<span class="label label-default label-lg">
							@if (Str::lower($upcoming->service->price) != 'free')
								${{ $upcoming->service->price }}
							@else
								{{ $upcoming->service->price }}
							@endif
							</span>

							@if ($hasOpenings and ($openSlots <= 5 and $openSlots > 1))
								&nbsp;<span class="label label-warning">Only {{ $openSlots }} slots left. Enroll today!</span>
							@endif

							@if ($hasOpenings and $openSlots == 1)
								&nbsp;<span class="label label-danger">Only 1 slot left. Enroll today!</span>
							@endif
						</dd>
						
						<dd><p class="help-block">{{ $upcoming->service->description }}</p></dd>

						<dd>
							<div class="visible-lg">
								<div class="btn-toolbar">
									@if ( ! $upcoming->service->isOneToOne())
										<div class="btn-group">
											<a href="{{ URL::route('event', array($upcoming->service->slug)) }}" class="btn btn-sm btn-default">More Info</a>
										</div>
									@endif

									@if (Auth::check() and $hasOpenings and ! Auth::user()->isAttending($upcoming->id))
										<div class="btn-group">
											<a href="#" class="btn btn-sm btn-primary js-enroll" data-appointment="{{ $upcoming->id }}">Enroll Now</a>
										</div>
									@endif
								</div>
							</div>
							<div class="hidden-lg">
								@if ( ! $upcoming->service->isOneToOne())
									<p><a href="{{ URL::route('event', array($upcoming->service->slug)) }}" class="btn btn-block btn-lg btn-default">More Info</a></p>
								@endif

								@if (Auth::check() and $hasOpenings and ! Auth::user()->isAttending($upcoming->id))
									<p><a href="#" class="btn btn-block btn-lg btn-primary js-enroll" data-appointment="{{ $upcoming->id }}">Enroll Now</a></p>
								@endif
							</div>
						</dd>
					@endif

					<?php ++$eventCount;?>
				@endforeach
				</dl>
			@else
				<div class="alert alert-warning">
					There are no scheduled events in the next 90 days. Check back regularly for more events and programs.
				</div>
			@endif

			<div class="visible-lg">
				<a href="{{ URL::route('events') }}" class="btn btn-block btn-default">View All Events</a>
			</div>
			<div class="hidden-lg">
				<a href="{{ URL::route('events') }}" class="btn btn-block btn-lg btn-default">View All Events</a>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
	<script type="text/javascript">
		
		$(document).on('click', '.js-enroll', function(e)
		{
			e.preventDefault();

			$.ajax({
				type: "POST",
				data: {
					'appointment': $(this).data('appointment')
				},
				url: "{{ URL::route('ajax.enroll') }}",
				success: function(data)
				{
					location.reload();
				}
			});
		});

		$(document).on('click', '.js-withdraw', function(e)
		{
			e.preventDefault();

			$.ajax({
				type: "POST",
				data: {
					'appointment': $(this).data('appointment')
				},
				url: "{{ URL::route('ajax.withdraw') }}",
				success: function(data)
				{
					location.reload();
				}
			});
		});

	</script>
@endsection