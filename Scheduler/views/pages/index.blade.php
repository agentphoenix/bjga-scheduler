@extends('layouts.master')

@section('title')
	Home
@stop

@section('content')
	@if (Auth::check())
		<div class="row">
			<div class="col-md-6 col-lg-6 col-md-push-6 col-lg-push-6">
				<h1 class="hidden-xs hidden-sm">&nbsp;</h1>
				<div class="visible-xs visible-sm">
					<div class="lg-btn-spacing">
						<a href="{{ URL::route('book.lesson') }}" class="btn btn-lg btn-default btn-block">
							<div class="icn-size-96">{{ $_icons['golf'] }}</div>
							Book a Lesson
						</a>
					</div>
					<div class="lg-btn-spacing">
						<a href="{{ URL::route('book.program') }}" class="btn btn-lg btn-default btn-block">
							<div class="icn-size-96">{{ $_icons['calendar'] }}</div>
							Enroll in a Program, Event or Clinic
						</a>
					</div>
					<div class="lg-btn-spacing">
						<a href="{{ URL::route('book.lesson') }}" class="btn btn-lg btn-default btn-block">
							<div class="icn-size-96">{{ $_icons['user'] }}</div>
							My Account
						</a>
					</div>
					<div class="lg-btn-spacing">
						<a href="{{ URL::route('book.lesson') }}" class="btn btn-lg btn-default btn-block">
							<div class="icn-size-96">{{ $_icons['logout'] }}</div>
							Log Out
						</a>
					</div>
				</div>
				<div class="visible-md visible-lg">
					<p><a href="{{ URL::route('book.lesson') }}" class="btn btn-lg btn-block btn-primary">Book a Lesson</a></p>
					<p><a href="{{ URL::route('book.program') }}" class="btn btn-lg btn-block btn-primary">Enroll in an Event, Program or Clinic</a></p>
					<p><a href="{{ URL::route('admin.user.edit', array($_currentUser->id)) }}" class="btn btn-lg btn-block btn-default">My Account</a></p>
					<p><a href="{{ URL::route('logout') }}" class="btn btn-lg btn-block btn-default">Log Out</a></p>
				</div>
			</div>

			<div class="col-md-6 col-lg-6 col-md-pull-6 col-lg-pull-6">
				<h1>My Appointments</h1>

				@if (count($myEvents) > 0)
					<dl>
						@foreach ($myEvents as $mine)
							<dt>{{ $mine->appointment->service->name }}</dt>
							<dd>
								<?php $appt = $mine->appointment->start;?>

								@if ($appt->isToday())
									Today
								@elseif ($appt->isTomorrow())
									Tommorow
								@else
									{{ $appt->format('l F jS, Y') }}
								@endif

								at {{ $appt->format('g:ia') }}
							</dd>
							<dd>
								<div class="visible-lg">
									<div class="btn-toolbar">
										<div class="btn-group">
											<a href="#" class="btn btn-sm btn-default">More Info</a>
										</div>
										<div class="btn-group">
											<a href="#" class="btn btn-sm btn-danger js-withdraw" data-appointment="{{ $mine->id }}">
												@if ($mine->appointment->service->isLesson())
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
										@if ($mine->appointment->service->isLesson())
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
					<div class="alert alert-warning">You don't have any upcoming appointments.</div>
				@endif
			</div>
		</div>
	@else
		<div class="row">
			<div class="col-lg-6 col-lg-offset-3">
				<h1>Log In</h1>

				@if (Session::has('loginMessage'))
					<div class="alert alert-danger">{{ Session::get('loginMessage') }}</div>
				@endif

				{{ Form::open(array('url' => 'login')) }}
					<div class="row">
						<div class="col-lg-12">
							<div class="form-group{{ ($errors->has('email')) ? ' has-error' : '' }}">
								<label class="control-label">Email Address</label>
								{{ Form::email('email', null, array('class' => 'form-control input-lg')) }}
								{{ $errors->first('email', '<p class="help-block">:message</p>') }}
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-lg-12">
							<div class="form-group{{ ($errors->has('password')) ? ' has-error' : '' }}">
								<label class="control-label">Password</label>
								{{ Form::password('password', array('class' => 'form-control input-lg')) }}
								{{ $errors->first('password', '<p class="help-block">:message</p>') }}
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-lg-12">
							<p>{{ Form::button("Log In", array('type' => 'submit', 'class' => 'btn btn-lg btn-block btn-primary')) }}</p>
							<p><a href="{{ URL::route('register') }}" class="btn btn-lg btn-block btn-default">Register</a></p>
							<p><a href="{{ URL::to('password/remind') }}" class="btn btn-block btn-link">Forgot Password?</a></p>
						</div>
					</div>
				{{ Form::close() }}
			</div>
		</div>
	@endif
@endsection