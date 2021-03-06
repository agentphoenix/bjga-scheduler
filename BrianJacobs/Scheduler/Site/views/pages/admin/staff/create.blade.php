@extends('layouts.master')

@section('title')
	Add Staff Member
@stop

@section('content')
	<h1>Add Staff Member</h1>

	@if ($_currentUser->access() > 1)
		<div class="visible-md visible-lg">
			<div class="btn-toolbar">
				<div class="btn-group">
					<a href="{{ URL::route('admin.staff.index') }}" class="btn btn-sm btn-default icn-size-16">{{ $_icons['back'] }}</a>
				</div>
			</div>
		</div>
		<div class="visible-xs visible-sm">
			<div class="row">
				<div class="col-xs-6 col-sm-3">
					<p><a href="{{ URL::route('admin.staff.index') }}" class="btn btn-block btn-lg btn-default icn-size-16">{{ $_icons['back'] }}</a></p>
				</div>
			</div>
		</div>
	@endif

	@if (count($users) > 0)
		{{ Form::open(array('route' => 'admin.staff.store')) }}
			<div class="row">
				<div class="col-sm-8 col-md-6 col-lg-4">
					<div class="form-group{{ ($errors->has('user_id')) ? ' has-error' : '' }}">
						<label class="control-label">User</label>
						{{ Form::select('user_id', $users, null, array('class' => 'form-control input-with-feedback')) }}
						{{ $errors->first('user_id', '<p class="help-block">:message</p>') }}
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-6 col-md-4 col-lg-2">
					<div class="{{ ($errors->has('access')) ? 'form-group has-error' : '' }}">
						<label class="control-label">Access Level</label>
						{{ Form::select('access', array('1' => 'Level 1', '2' => 'Level 2', '3' => 'Level 3', '4' => 'Level 4'), 1, array('class' => 'form-control input-with-feedback')) }}
						{{ $errors->first('access', '<p class="help-block">:message</p>') }}
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12">
					<p class="help-block">Most staff members should have level <strong>1</strong> access. If there is a question about this, contact David.</p>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-8 col-md-6 col-lg-4">
					<div class="form-group">
						<label class="control-label">Title</label>
						{{ Form::text('title', null, array('class' => 'form-control')) }}
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
						{{ Form::submit('Create', array('class' => 'btn btn-lg btn-primary')) }}
					</div>
					<div class="visible-xs visible-sm">
						{{ Form::submit('Create', array('class' => 'btn btn-lg btn-block btn-primary')) }}
					</div>
				</div>
			</div>
		{{ Form::close() }}
	@else
		{{ partial('common.alert', array('class' => 'warning', 'content' => "There are no available users to add as staff members.")) }}
	@endif
@stop