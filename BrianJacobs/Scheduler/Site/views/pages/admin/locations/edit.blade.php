@extends('layouts.master')

@section('title')
	Edit Location
@stop

@section('content')
	<h1>Edit Location <small>{{ $location->present()->name }}</small></h1>

	@if ($_currentUser->access() > 1)
		<div class="visible-md visible-lg">
			<div class="btn-toolbar">
				<div class="btn-group">
					<a href="{{ route('admin.locations.index') }}" class="btn btn-sm btn-default icn-size-16">{{ $_icons['back'] }}</a>
				</div>
			</div>
		</div>
		<div class="visible-xs visible-sm">
			<div class="row">
				<div class="col-xs-6 col-sm-3">
					<p><a href="{{ route('admin.locations.index') }}" class="btn btn-lg btn-block btn-default icn-size-16">{{ $_icons['back'] }}</a></p>
				</div>
			</div>
		</div>
	@endif

	{{ Form::model($location, array('route' => array('admin.locations.update', $location->id), 'method' => 'put')) }}
		<div class="row">
			<div class="col-sm-6 col-md-4">
				<div class="form-group{{ ($errors->has('name')) ? ' has-error' : '' }}">
					<label class="control-label">Name</label>
					{{ Form::text('name', null, array('class' => 'form-control input-with-feedback')) }}
					{{ $errors->first('name', '<p class="help-block">:message</p>') }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6 col-md-4">
				<div class="form-group{{ ($errors->has('url')) ? ' has-error' : '' }}">
					<label class="control-label">Website</label>
					{{ Form::text('url', null, array('class' => 'form-control input-with-feedback')) }}
					{{ $errors->first('url', '<p class="help-block">:message</p>') }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6 col-md-4">
				<div class="form-group{{ ($errors->has('phone')) ? ' has-error' : '' }}">
					<label class="control-label">Phone Number</label>
					{{ Form::text('phone', null, array('class' => 'form-control input-with-feedback')) }}
					{{ $errors->first('phone', '<p class="help-block">:message</p>') }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6">
				<div class="form-group{{ ($errors->has('address')) ? ' has-error' : '' }}">
					<label class="control-label">Address</label>
					{{ Form::textarea('address', null, array('class' => 'form-control', 'rows' => 3)) }}
					{{ $errors->first('address', '<p class="help-block">:message</p>') }}
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