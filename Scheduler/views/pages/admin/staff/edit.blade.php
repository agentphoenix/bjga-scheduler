@extends('layouts.master')

@section('title')
	Edit Staff Member
@stop

@section('content')
	<h1>Edit Staff Member <small>{{ $staff->user->name }}</small></h1>

	@if ($_currentUser->access() > 1)
		<div class="visible-md visible-lg">
			<div class="btn-toolbar">
				<div class="btn-group">
					<a href="{{ URL::route('admin.staff.index') }}" class="btn btn-sm btn-default icn-size-16">{{ $_icons['back'] }}</a>
				</div>
				<div class="btn-group">
					<a href="{{ URL::route('admin.user.edit', array($_currentUser->id)) }}" class="btn btn-default">Edit My User Account</a>
				</div>
				<div class="btn-group">
					<a href="{{ URL::route('admin.staff.schedule') }}" class="btn btn-default">Manage My Schedule</a>
				</div>
			</div>
		</div>
		<div class="visible-xs visible-sm">
			<div class="row">
				<div class="col-sm-4">
					<p><a href="{{ URL::route('admin.staff.index') }}" class="btn btn-block btn-lg btn-default icn-size-16">{{ $_icons['back'] }}</a></p>
				</div>
				<div class="col-sm-4">
					<p><a href="{{ URL::route('admin.user.edit', array($_currentUser->id)) }}" class="btn btn-block btn-lg btn-default">Edit User Account</a></p>
				</div>
				<div class="col-sm-4">
					<p><a href="{{ URL::route('admin.staff.schedule') }}" class="btn btn-lg btn-block btn-default">Manage My Schedule</a></p>
				</div>
			</div>
		</div>
	@endif

	{{ Form::model($staff, array('route' => array('admin.staff.update', $staff->id), 'method' => 'put')) }}
		@if ($_currentUser->access() > 1)
			<div class="row">
				<div class="col-sm-8 col-md-6 col-lg-4">
					<div class="form-group">
						<label class="control-label">Title</label>
						{{ Form::text('title', null, array('class' => 'form-control')) }}
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-6 col-md-4 col-lg-2">
					<div class="form-group{{ ($errors->has('access')) ? ' has-error' : '' }}">
						<label class="control-label">Access Level</label>
						{{ Form::select('access', array('1' => 'Level 1', '2' => 'Level 2', '3' => 'Level 3'), null, array('class' => 'form-control input-with-feedback')) }}
						{{ $errors->first('access', '<p class="help-block">:message</p>') }}
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-8 col-md-6 col-lg-4">
					<div class="form-group">
						<label class="control-label">Available for Instruction</label>
						<div>
							<label class="radio-inline text-sm">{{ Form::radio('instruction', (int) true) }} Yes</label>
							<label class="radio-inline text-sm">{{ Form::radio('instruction', (int) false) }} No</label>
						</div>
					</div>
				</div>
			</div>
		@endif

		<div class="row">
			<div class="col-md-10 col-lg-8">
				<div class="form-group">
					<label class="control-label">Bio</label>
					{{ Form::textarea('bio', null, array('class' => 'form-control', 'rows' => 8)) }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<div class="visible-md visible-lg">
					{{ Form::submit('Update', array('class' => 'btn btn-lg btn-primary')) }}
				</div>
				<div class="visible-xs visible-sm">
					{{ Form::submit('Update', array('class' => 'btn btn-lg btn-block btn-primary')) }}
				</div>
			</div>
		</div>
	{{ Form::close() }}
@stop