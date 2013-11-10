@extends('layouts.master')

@section('title')
	Register
@endsection

@section('content')
	<h1>Register</h1>

	@if (Session::has('registerMessage'))
		<div class="alert alert-danger">{{ Session::get('registerMessage') }}</div>
	@endif

	<p>In order to book private lessons and golf schools, join teams and attend events and programs, you'll need to register. Join us today and take your game inside the ropes with Brian Jacobs Golf!</p>

	<hr>

	{{ Form::open(array('url' => 'register')) }}
		<div class="row">
			<div class="col-xs-12 col-lg-4">
				<div class="form-group{{ ($errors->has('name')) ? ' has-error' : '' }}">
					<label class="control-label">Name</label>
					{{ Form::text('name', null, array('class' => 'form-control')) }}
					{{ $errors->first('name', '<p class="help-block">:message</p>') }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 col-lg-4">
				<div class="form-group{{ ($errors->has('email')) ? ' has-error' : '' }}">
					<label class="control-label">Email Address</label>
					{{ Form::email('email', null, array('class' => 'form-control')) }}
					{{ $errors->first('email', '<p class="help-block">:message</p>') }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 col-lg-4">
				<div class="form-group{{ ($errors->has('password')) ? ' has-error' : '' }}">
					<label class="control-label">Password</label>
					{{ Form::password('password', array('class' => 'form-control')) }}
					{{ $errors->first('password', '<p class="help-block">:message</p>') }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 col-lg-4">
				<div class="form-group{{ ($errors->has('password_confirm')) ? ' has-error' : '' }}">
					<label class="control-label">Confirm Password</label>
					{{ Form::password('password_confirm', array('class' => 'form-control')) }}
					{{ $errors->first('password_confirm', '<p class="help-block">:message</p>') }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 col-lg-4">
				<div class="form-group{{ ($errors->has('phone')) ? ' has-error' : '' }}">
					<label class="control-label">Phone Number</label>
					{{ Form::text('phone', null, array('class' => 'form-control')) }}
					{{ $errors->first('phone', '<p class="help-block">:message</p>') }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 col-lg-4">
				<div class="visible-lg">
					<div class="btn-toolbar">
						<div class="btn-group">
							{{ Form::button("Register", array('type' => 'submit', 'class' => 'btn btn-primary')) }}
						</div>
						<div class="btn-group">
							<a href="{{ URL::route('home') }}" class="btn btn-default">Home</a>
						</div>
					</div>
				</div>
				<div class="hidden-lg">
					<p>{{ Form::submit("Register", array('class' => 'btn btn-block btn-lg btn-primary')) }}</p>

					<p><a href="{{ URL::route('home') }}" class="btn btn-block btn-lg btn-default">Home</a></p>
				</div>
			</div>
		</div>
	{{ Form::close() }}
@endsection