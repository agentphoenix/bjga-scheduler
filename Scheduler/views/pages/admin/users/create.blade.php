@extends('layouts.master')

@section('title')
	Create User
@endsection

@section('content')
	<h1>Create User</h1>

	@if ($_currentUser->access() > 1)
		<div class="hidden-xs">
			<div class="btn-toolbar">
				<div class="btn-group">
					<a href="{{ URL::route('admin.user.index') }}" class="btn btn-default icn-size-16">{{ $_icons['back'] }}</a>
				</div>
			</div>
		</div>
		<div class="visible-xs">
			<div class="row">
				<div class="col-xs-12 col-sm-6">
					<p><a href="{{ URL::route('admin.user.index') }}" class="btn btn-lg btn-block btn-default icn-size-16">{{ $_icons['back'] }}</a></p>
				</div>
			</div>
		</div>
	@endif

	{{ Form::open(array('route' => 'admin.user.store')) }}
		<div class="row">
			<div class="col-lg-4">
				<div class="form-group{{ ($errors->has('name')) ? ' has-error' : '' }}">
					<label class="label-control">Name</label>
					{{ Form::text('name', null, array('class' => 'form-control input-with-feedback')) }}
					{{ $errors->first('name', '<p class="help-block">:message</p>') }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-4">
				<div class="form-group{{ ($errors->has('password')) ? ' has-error' : '' }}">
					<label class="label-control">Password</label>
					{{ Form::password('password', array('class' => 'form-control input-with-feedback')) }}
					{{ $errors->first('password', '<p class="help-block">:message</p>') }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-4">
				<div class="form-group{{ ($errors->has('password_confirm')) ? ' has-error' : '' }}">
					<label class="label-control">Confirm Password</label>
					{{ Form::password('password_confirm', array('class' => 'form-control input-with-feedback')) }}
					{{ $errors->first('password_confirm', '<p class="help-block">:message</p>') }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-4">
				<div class="form-group{{ ($errors->has('email')) ? ' has-error' : '' }}">
					<label class="label-control">Email Address</label>
					{{ Form::email('email', null, array('class' => 'form-control input-with-feedback')) }}
					{{ $errors->first('email', '<p class="help-block">:message</p>') }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-4">
				<div class="form-group{{ ($errors->has('phone')) ? ' has-error' : '' }}">
					<label class="label-control">Phone Number</label>
					{{ Form::text('phone', null, array('class' => 'form-control input-with-feedback')) }}
					{{ $errors->first('phone', '<p class="help-block">:message</p>') }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-6">
				<div class="form-group">
					<label class="label-control">Address</label>
					{{ Form::textarea('address', null, array('class' => 'form-control', 'rows' => 3)) }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<div class="visible-lg">
					{{ Form::button('Submit', array('type' => 'submit', 'class' => 'btn btn-lg btn-primary')) }}
				</div>
				<div class="hidden-lg">
					{{ Form::button('Submit', array('type' => 'submit', 'class' => 'btn btn-lg btn-block btn-primary')) }}
				</div>
			</div>
		</div>
	{{ Form::close() }}
@endsection