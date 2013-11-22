@extends('layouts.master')

@section('title')
	Reset Password
@endsection

@section('content')
	@if (Session::has('error'))
		<br>
		<div class="alert alert-danger">{{ trans(Session::get('reason')) }}</div>
	@endif

	<h1>Reset Password</h1>

	{{ Form::open(array('url' => "password/reset/{$token}")) }}
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
				<div class="form-group">
					<label class="control-label">Password</label>
					{{ Form::password('password', array('class' => 'form-control')) }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 col-lg-4">
				<div class="form-group">
					<label class="control-label">Confirm Password</label>
					{{ Form::password('password_confirmation', array('class' => 'form-control')) }}
				</div>
			</div>
		</div>

		{{ Form::hidden('token', $token) }}

		<div class="row">
			<div class="col-xs-12 col-lg-4">
				<div class="visible-lg">
					<div class="btn-toolbar">
						<div class="btn-group">
							{{ Form::button("Reset", array('type' => 'submit', 'class' => 'btn btn-primary')) }}
						</div>
					</div>
				</div>
				<div class="hidden-lg">
					<p>{{ Form::submit("Reset", array('class' => 'btn btn-block btn-lg btn-primary')) }}</p>
				</div>
			</div>
		</div>
	{{ Form::close() }}
@endsection