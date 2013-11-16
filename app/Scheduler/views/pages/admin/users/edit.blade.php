@extends('layouts.master')

@section('title')
	Edit User
@endsection

@section('content')
	<h1>Edit User <small>{{ $user->name }}</small></h1>

	<div class="visible-lg">
		<div class="btn-toolbar">
			@if ($_currentUser->access() > 1)
				<div class="btn-group">
					<a href="{{ URL::route('admin.user.index') }}" class="btn btn-default icn-size-16">{{ $_icons['back'] }}</a>
				</div>
			@endif

			@if ($_currentUser->id == $user->id)
				<div class="btn-group">
					<a href="#" class="btn btn-default js-user-action icn-size-16" data-action="password" data-id="{{ $user->id }}">Change Password</a>
				</div>
			@endif
		</div>
	</div>
	<div class="hidden-lg">
		<div class="row">
			@if ($_currentUser->access() > 1)
				<div class="col-xs-12 col-sm-6">
					<p><a href="{{ URL::route('admin.user.index') }}" class="btn btn-block btn-lg btn-default icn-size-16">{{ $_icons['back'] }}</a></p>
				</div>
			@endif

			@if ($_currentUser->id == $user->id)
				<div class="col-xs-12 col-sm-6">
					<p><a href="#" class="btn btn-block btn-lg btn-default js-user-action" data-action="password" data-id="{{ $user->id }}">Change Password</a></p>
				</div>
			@endif
		</div>
	</div>

	{{ Form::model($user, array('route' => array('admin.user.update', $user->id), 'method' => 'put')) }}
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
					{{ Form::textarea('address', null, array('class' => 'form-control', 'rows' => 5)) }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<div class="visible-lg">
					{{ Form::button('Submit', array('type' => 'submit', 'class' => 'btn btn-primary')) }}
				</div>
				<div class="hidden-lg">
					{{ Form::button('Submit', array('type' => 'submit', 'class' => 'btn btn-lg btn-block btn-primary')) }}
				</div>
			</div>
		</div>
	{{ Form::close() }}

	{{ modal(array('id' => 'changePassword', 'header' => "Change Password")) }}
@endsection

@section('scripts')
	<script type="text/javascript">
		
		$('.js-user-action').on('click', function(e)
		{
			e.preventDefault();

			var action = $(this).data('action');
			var id = $(this).data('id');

			if (action == 'password')
			{
				$('#changePassword').modal({
					remote: "{{ URL::to('ajax/user/password') }}/" + id
				}).modal('show');
			}
		});

	</script>
@endsection