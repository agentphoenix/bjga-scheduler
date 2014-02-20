@extends('layouts.master')

@section('title')
	Log In
@stop

@section('content')
	<div class="row">
		<div class="col-sm-10 col-md-6 col-lg-6 col-sm-offset-1 col-md-offset-3 col-lg-offset-3">
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
						<p>{{ Form::submit("Log In", array('class' => 'btn btn-lg btn-block btn-primary')) }}</p>

						<div class="row">
							<div class="col-sm-6 col-md-6 col-lg-6">
								<p><a href="{{ URL::route('register') }}" class="btn btn-lg btn-block btn-default">Register</a></p>
							</div>
							<div class="col-sm-6 col-md-6 col-lg-6">
								<p><a href="{{ URL::to('password/remind') }}" class="btn btn-lg btn-block btn-default">Forgot Password?</a></p>
							</div>
						</div>
					</div>
				</div>
			{{ Form::close() }}
		</div>
	</div>
@stop