@extends('layouts.master')

@section('title')
	Create Lesson Service
@endsection

@section('content')
	<h1>Create Lesson Service</h1>

	@if ($_currentUser->access() > 1)
		<div class="visible-md visible-lg">
			<div class="btn-toolbar">
				<div class="btn-group">
					<a href="{{ URL::route('admin.service.index') }}" class="btn btn-sm btn-default icn-size-16">{{ $_icons['back'] }}</a>
				</div>
			</div>
		</div>
		<div class="visible-xs visible-sm">
			<div class="row">
				<div class="col-sm-3">
					<p><a href="{{ URL::route('admin.service.index') }}" class="btn btn-lg btn-block btn-default icn-size-16">{{ $_icons['back'] }}</a></p>
				</div>
			</div>
		</div>
	@endif

	{{ Form::open(array('route' => 'admin.service.store')) }}
		<div class="row">
			<div class="col-lg-4">
				<div class="form-group{{ ($errors->has('staff_id')) ? ' has-error' : '' }}">
					<label class="control-label">Instructor</label>
					{{ Form::select('staff_id', $staff, null, array('class' => 'form-control input-with-feedback')) }}
					{{ $errors->first('staff_id', '<p class="help-block">:message</p>') }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-4">
				<div class="form-group{{ ($errors->has('name')) ? ' has-error' : '' }}">
					<label class="control-label">Service Name</label>
					{{ Form::text('name', null, array('class' => 'form-control input-with-feedback')) }}
					{{ $errors->first('name', '<p class="help-block">:message</p>') }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-6">
				<div class="form-group">
					<label class="control-label">Description</label>
					{{ Form::textarea('description', null, array('class' => 'form-control', 'rows' => 3)) }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-2">
				<label class="control-label">Price</label>
				<div class="input-group">
					<span class="input-group-addon"><strong>$</strong></span>
					{{ Form::text('price', null, array('class' => 'form-control')) }}
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<p class="help-block">If the service is free, enter zero. Enter the price <em>per instance</em>!</p>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-2">
				<label class="control-label">Duration</label>
				{{ Form::text('duration', 60, array('class' => 'form-control')) }}
				<p class="help-block">Duration in minutes.</p>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-2">
				<div class="form-group{{ ($errors->has('occurrences')) ? ' has-error' : '' }}">
					<label class="control-label">Occurrences</label>
					{{ Form::text('occurrences', 1, array('class' => 'form-control input-with-feedback')) }}
					{{ $errors->first('occurrences', '<p class="help-block">:message</p>') }}
				</div>
			</div>
			<div class="col-lg-3">
				<div class="form-group">
					<label class="control-label">Occurrence Schedule</label>
					{{ Form::text('occurrences_schedule', 0, array('class' => 'form-control input-with-feedback')) }}
					<p class="help-block">Days between occurrences.</p>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-3">
				<div class="form-group">
					<label class="control-label">Status</label>
					<div>
						<label class="radio-inline text-sm">{{ Form::radio('status', (int) true, true) }} Active</label>
						<label class="radio-inline text-sm">{{ Form::radio('status', (int) false) }} Inactive</label>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-3">
				<div class="form-group">
					<label class="control-label">Loyalty Program?</label>
					<div>
						<label class="radio-inline text-sm">{{ Form::radio('loyalty', (int) true) }} Yes</label>
						<label class="radio-inline text-sm">{{ Form::radio('loyalty', (int) false, true) }} No</label>
					</div>
				</div>
			</div>
		</div>

		{{ Form::hidden('category', 'lesson') }}
		{{ Form::hidden('user_limit', 1) }}

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
@stop