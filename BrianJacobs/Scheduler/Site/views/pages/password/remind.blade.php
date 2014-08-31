@extends('layouts.master')

@section('title')
	Reset Password
@stop

@section('content')
	<div class="row">
		<div class="col-lg-6 col-lg-offset-3">
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
						<p>{{ Form::submit("Send Reset Confirmation", array('class' => 'btn btn-lg btn-block btn-primary')) }}</p>
						<p><a href="{{ URL::route('home') }}" class="btn btn-link btn-block">Cancel</a></p>
					</div>
				</div>
			{{ Form::close() }}
		</div>
	</div>
@stop