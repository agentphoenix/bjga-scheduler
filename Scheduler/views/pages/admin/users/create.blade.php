@extends('layouts.master')

@section('title')
	Create User
@stop

@section('content')
	<h1>Create User</h1>

	@if ($_currentUser->access() > 1)
		<div class="visible-md visible-lg">
			<div class="btn-toolbar">
				<div class="btn-group">
					<a href="{{ URL::route('admin.user.index') }}" class="btn btn-sm btn-default icn-size-16">{{ $_icons['back'] }}</a>
				</div>
			</div>
		</div>
		<div class="visible-xs visible-sm">
			<div class="row">
				<div class="col-xs-6 col-sm-3">
					<p><a href="{{ URL::route('admin.user.index') }}" class="btn btn-lg btn-block btn-default icn-size-16">{{ $_icons['back'] }}</a></p>
				</div>
			</div>
		</div>
	@endif

	{{ Form::open(array('route' => 'admin.user.store')) }}
		<div class="row">
			<div class="col-sm-6 col-md-6 col-lg-4">
				<div class="form-group{{ ($errors->has('name')) ? ' has-error' : '' }}">
					<label class="control-label">Name</label>
					{{ Form::text('name', null, array('class' => 'form-control input-with-feedback')) }}
					{{ $errors->first('name', '<p class="help-block">:message</p>') }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6 col-md-6 col-lg-4">
				<div class="form-group{{ ($errors->has('password')) ? ' has-error' : '' }}">
					<label class="control-label">Password</label>
					{{ Form::password('password', array('class' => 'form-control input-with-feedback')) }}
					{{ $errors->first('password', '<p class="help-block">:message</p>') }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6 col-md-6 col-lg-4">
				<div class="form-group{{ ($errors->has('password_confirm')) ? ' has-error' : '' }}">
					<label class="control-label">Confirm Password</label>
					{{ Form::password('password_confirm', array('class' => 'form-control input-with-feedback')) }}
					{{ $errors->first('password_confirm', '<p class="help-block">:message</p>') }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6 col-md-6 col-lg-4">
				<div class="form-group{{ ($errors->has('email')) ? ' has-error' : '' }}">
					<label class="control-label">Email Address</label>
					{{ Form::email('email', null, array('class' => 'form-control input-with-feedback')) }}
					{{ $errors->first('email', '<p class="help-block">:message</p>') }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6 col-md-6 col-lg-4">
				<div class="form-group{{ ($errors->has('phone')) ? ' has-error' : '' }}">
					<label class="control-label">Phone Number</label>
					{{ Form::text('phone', null, array('class' => 'form-control input-with-feedback')) }}
					{{ $errors->first('phone', '<p class="help-block">:message</p>') }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-8 col-md-8 col-lg-6">
				<div class="form-group">
					<label class="control-label">Address</label>
					{{ Form::textarea('address', null, array('class' => 'form-control', 'rows' => 3)) }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<div class="visible-md visible-lg">
					{{ Form::button('Submit', array('type' => 'submit', 'class' => 'btn btn-lg btn-primary')) }}
				</div>
				<div class="visible-xs visible-sm">
					{{ Form::button('Submit', array('type' => 'submit', 'class' => 'btn btn-lg btn-block btn-primary')) }}
				</div>
			</div>
		</div>
	{{ Form::close() }}
@stop