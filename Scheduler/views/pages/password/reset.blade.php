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
			@endif

			<h1>Reset Password</h1>

			{{ Form::open(array('url' => "password/reset/{$token}")) }}
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
						<div class="form-group">
							<label class="control-label">Password</label>
							{{ Form::password('password', array('class' => 'form-control input-lg')) }}
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-lg-12">
						<div class="form-group">
							<label class="control-label">Confirm Password</label>
							{{ Form::password('password_confirmation', array('class' => 'form-control input-lg')) }}
						</div>
					</div>
				</div>

				{{ Form::hidden('token', $token) }}

				<div class="row">
					<div class="col-lg-12">
						<p>{{ Form::submit("Reset Password", array('class' => 'btn btn-lg btn-block btn-primary')) }}</p>
					</div>
				</div>
			{{ Form::close() }}
		</div>
	</div>
@stop