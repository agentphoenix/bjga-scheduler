@extends('layouts.master')

@section('title')
	Reset Password
@endsection

@section('content')
	@if (Session::has('error'))
		<br>
		<div class="alert alert-danger">{{ trans(Session::get('reason')) }}</div>
	@elseif (Session::has('success'))
		<br>
		<div class="alert alert-success">An email with the password reset has been sent.</div>
	@endif

	<h1>Reset Password</h1>

	<p>In order to book private lessons and golf schools, join teams and attend events and programs, you'll need to register. Join us today and take your game inside the ropes with Brian Jacobs Golf!</p>

	<hr>

	{{ Form::open(array('url' => 'password/remind')) }}
		<div class="row">
			<div class="col-xs-12 col-lg-4">
				<div class="form-group">
					<label class="control-label">Email Address</label>
					{{ Form::email('email', null, array('class' => 'form-control')) }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 col-lg-4">
				<div class="visible-lg">
					<div class="btn-toolbar">
						<div class="btn-group">
							{{ Form::button("Reset", array('type' => 'submit', 'class' => 'btn btn-primary')) }}
						</div>
						<div class="btn-group">
							<a href="{{ URL::route('home') }}" class="btn btn-link">Cancel</a>
						</div>
					</div>
				</div>
				<div class="hidden-lg">
					<p>{{ Form::submit("Reset", array('class' => 'btn btn-block btn-lg btn-primary')) }}</p>

					<p><a href="{{ URL::route('home') }}" class="btn btn-block btn-lg btn-link">Cancel</a></p>
				</div>
			</div>
		</div>
	{{ Form::close() }}
@endsection