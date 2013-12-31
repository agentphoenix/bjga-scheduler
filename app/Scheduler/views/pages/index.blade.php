@extends('layouts.master')

@section('title')
	Home
@endsection

@section('content')
	@if (Auth::check())
		<div class="row">
			<div class="col-md-6 col-lg-6 col-md-push-6 col-lg-push-6">
				<h1>&nbsp;</h1>
				<p><a href="{{ URL::route('book.index') }}" class="btn btn-lg btn-block btn-primary">Book Now</a></p>
				<p><a href="{{ URL::route('book.index') }}" class="btn btn-lg btn-block btn-default">My Account</a></p>
				<p><a href="{{ URL::route('book.index') }}" class="btn btn-lg btn-block btn-default">Find Events</a></p>
				<p><a href="{{ URL::route('book.index') }}" class="btn btn-lg btn-block btn-default">Log Out</a></p>
			</div>

			<div class="col-md-6 col-lg-6 col-md-pull-6 col-lg-pull-6">
				@if ($unscheduled->count() < 0)
					<h1 class="text-warning"><span class="icn-size-32">{{ $_icons['warning'] }}</span> Unscheduled Appointments</h1>

					<div class="panel panel-warning">
						<div class="panel-heading"><h3 class="panel-title">Select a pending appointment to schedule</h3></div>
						<div class="panel-body">
							<div class="data-table data-table-bordered data-table-striped">
								@foreach ($unscheduled as $u)
									<div class="row">
										<div class="col-lg-6">
											<p><strong>{{ $u->appointment->service->name }}</strong></p>
										</div>
										<div class="col-lg-6">
											<div class="btn-toolbar pull-right">
												<div class="btn-group">
													<a href="#" class="btn btn-sm btn-default icn-size-16">{{ $_icons['calendar'] }}</a>
												</div>
											</div>
										</div>
									</div>
								@endforeach
							</div>
						</div>
					</div>
				@endif

				<h1><span class="icn-size-32">{{ $_icons['user'] }}</span> My Appointments</h1>

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