@extends('layouts.master')

@section('title')
	Reset Password
@endsection

@section('content')
	<div class="row">
		<div class="col-lg-6 col-lg-offset-3">
			@if (Session::has('error'))
				<br>
				<div class="alert alert-danger">{{ trans(Session::get('reason')) }}</div>
			@elseif (Session::has('success'))
				<br>
				<div class="alert alert-success">An email with the password reset has been sent.</div>
			@endif

			<h1>Reset Password</h1>

			<p>To reset your password, enter your email address below. You'll be sent an email with a confirmation link where you'll be able to set your new password.</p>

			{{ Form::open(array('url' => 'password/remind')) }}
				<div class="row">
					<div class="col-lg-12">
						<div class="form-group">
							<label class="control-label">Email Address</label>
							{{ Form::email('email', null, array('class' => 'form-control input-lg')) }}
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-lg-12">
						<p>{{ Form::submit("Reset", array('class' => 'btn btn-block btn-lg btn-primary')) }}</p>
						<p><a href="{{ URL::route('home') }}" class="btn btn-block btn-link">Cancel</a></p>
					</div>
				</div>
			{{ Form::close() }}
		</div>
	</div>
@stop