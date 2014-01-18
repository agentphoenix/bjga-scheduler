@extends('layouts.master')

@section('title')
	Edit Lesson Service
@stop

@section('content')
	<h1>Edit Lesson Service <small>{{ $service->name }}</small></h1>

	@if ($_currentUser->access() > 1)
		<div class="hidden-xs">
			<div class="btn-toolbar">
				<div class="btn-group">
					<a href="{{ URL::route('admin.service.index') }}" class="btn btn-default icn-size-16">{{ $_icons['back'] }}</a>
				</div>
			</div>
		</div>
		<div class="visible-xs">
			<div class="row">
				<div class="col-sm-6">
					<p><a href="{{ URL::route('admin.service.index') }}" class="btn btn-lg btn-block btn-default icn-size-16">{{ $_icons['back'] }}</a></p>
				</div>
			</div>
		</div>
	@endif

	{{ Form::model($service, array('route' => array('admin.service.update', $service->id), 'method' => 'put')) }}
		<div class="row">
			<div class="col-lg-3">
				<div class="form-group{{ ($errors->has('staff_id')) ? ' has-error' : '' }}">
					<label class="label-control">Staff Member</label>
					{{ Form::select('staff_id', $staff, null, array('class' => 'form-control input-with-feedback')) }}
					{{ $errors->first('staff_id', '<p class="help-block">:message</p>') }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-4">
				<div class="form-group{{ ($errors->has('name')) ? ' has-error' : '' }}">
					<label class="label-control">Service Name</label>
					{{ Form::text('name', null, array('class' => 'form-control input-with-feedback')) }}
					{{ $errors->first('name', '<p class="help-block">:message</p>') }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-6">
				<div class="form-group">
					<label class="label-control">Description</label>
					{{ Form::textarea('description', null, array('class' => 'form-control', 'rows' => 3)) }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-2">
				<label class="label-control">Price</label>
				<div class="input-group">
					<span class="input-group-addon"><strong>$</strong></span>
					{{ Form::text('price', null, array('class' => 'form-control')) }}
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<p class="help-block">If the service is free, enter zero.</p>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-2">
				<label class="label-control">Duration</label>
				{{ Form::text('duration', null, array('class' => 'form-control')) }}
				<p class="help-block">Duration in minutes.</p>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-2">
				<div class="form-group{{ ($errors->has('occurrences')) ? ' has-error' : '' }}">
					<label class="label-control">Occurrences</label>
					{{ Form::text('occurrences', null, array('class' => 'form-control input-with-feedback js-occurrences')) }}
					{{ $errors->first('occurrences', '<p class="help-block">:message</p>') }}
				</div>
			</div>
			<div class="col-lg-3">
				<div class="form-group">
					<label class="label-control">Occurrence Schedule</label>
					{{ Form::text('occurrences_schedule', null, array('class' => 'form-control input-with-feedback')) }}
					<p class="help-block">Number of days between occurrences.</p>
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
@stop