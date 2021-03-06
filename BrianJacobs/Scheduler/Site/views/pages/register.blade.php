@extends('layouts.master')

@section('title')
	Register
@stop

@section('content')
	<div class="row">
		<div class="col-sm-10 col-md-6 col-lg-6 col-sm-offset-1 col-md-offset-3 col-lg-offset-3">
			<h1>Register</h1>

			@if (Session::has('registerMessage'))
				<div class="alert alert-danger">{{ Session::get('registerMessage') }}</div>
			@endif

			<p>In order to book private lessons and attend programs, you'll need to register. Join us today and take your game inside the ropes with Brian Jacobs Golf!</p>

			<hr>

			{{ Form::open(array('url' => 'register')) }}
				<div class="row">
					<div class="col-lg-12">
						<div class="form-group{{ ($errors->has('name')) ? ' has-error' : '' }}">
							<label class="control-label">Name</label>
							{{ Form::text('name', null, array('class' => 'form-control input-lg')) }}
							{{ $errors->first('name', '<p class="help-block">:message</p>') }}
						</div>
					</div>
				</div>

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
						<div class="form-group{{ ($errors->has('password_confirm')) ? ' has-error' : '' }}">
							<label class="control-label">Confirm Password</label>
							{{ Form::password('password_confirm', array('class' => 'form-control input-lg')) }}
							{{ $errors->first('password_confirm', '<p class="help-block">:message</p>') }}
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-lg-12">
						<div class="form-group{{ ($errors->has('phone')) ? ' has-error' : '' }}">
							<label class="control-label">Phone Number</label>
							{{ Form::text('phone', null, array('class' => 'form-control input-lg')) }}
							{{ $errors->first('phone', '<p class="help-block">:message</p>') }}
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-lg-12">
						<div class="form-group{{ ($errors->has('confirm')) ? ' has-error' : '' }}">
							<label class="control-label">Anti-Spam Confirmation</label>
							<p>In order to prevent spam, please type in the following number to the field below: <strong>{{ $confirmNumber }}</strong>.</p>
							{{ Form::text('confirm', null, array('class' => 'form-control input-lg')) }}
							{{ $errors->first('confirm', '<p class="help-block">:message</p>') }}
						</div>
					</div>
				</div>

				<!--<div class="row">
					<div class="col-lg-12">
						<div class="form-group">
							<div>
								<label class="checkbox-inline text-sm">
									{{ Form::checkbox('newsletter_optin', 1, true) }} Sign up for our newsletter
								</label>
							</div>
						</div>
					</div>
				</div>-->

				<div class="row">
					<div class="col-lg-12">
						<p>{{ Form::submit("Register", array('class' => 'btn btn-lg btn-block btn-primary')) }}</p>
						<p><a href="{{ URL::route('home') }}" class="btn btn-lg btn-block btn-default">Home</a></p>
					</div>
				</div>
			{{ Form::close() }}
		</div>
	</div>
@stop