@extends('layouts.master')

@section('content')
	{{ Form::open(array('route' => 'admin.service.store')) }}
		<div class="row">
			<div class="col-lg-4">
				<div class="form-group{{ ($errors->has('category_id')) ? ' has-error' : '' }}">
					<label class="control-label">Category</label>
					{{ Form::select('category_id', $categories, null, array('class' => 'form-control')) }}
					{{ $errors->first('category_id', '<p class="help-block">:message</p>') }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-4">
				<div class="form-group{{ ($errors->has('staff_id')) ? ' has-error' : '' }}">
					<label class="control-label">Staff Member</label>
					{{ Form::select('staff_id', $staff, null, array('class' => 'form-control')) }}
					{{ $errors->first('staff_id', '<p class="help-block">:message</p>') }}
				</div>
			</div>
		</div>

		<div id="ajax-container"></div>

		<div class="row hide" id="submitBtn">
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

	@if ($_currentUser->access() > 1)
		<div class="visible-lg">
			<div class="btn-toolbar">
				<div class="btn-group">
					<a href="{{ URL::route('admin.service.index') }}" class="btn btn-default icn-size-16">{{ $_icons['back'] }}</a>
				</div>
			</div>
		</div>
		<div class="hidden-lg">
			<div class="row">
				<div class="col-xs-12 col-sm-6">
					<p><a href="{{ URL::route('admin.service.index') }}" class="btn btn-block btn-lg btn-default icn-size-16">{{ $_icons['back'] }}</a></p>
				</div>
			</div>
		</div>
	@endif
	
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
			{{ Form::text('price', null, array('class' => 'form-control')) }}
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<p class="help-block">If the service is free, leave this blank. Do not enter a dollar sign ($) at the beginning of the price.</p>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-2">
			<div class="form-group">
				<label class="control-label">Duration</label>
				{{ Form::text('duration', 60, array('class' => 'form-control')) }}
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<p class="help-block">Enter the length of the service in minutes</p>
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
	</div>

	<div class="row">
		<div class="col-lg-8">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h2 class="panel-title">Additional Services Included</h2>
				</div>
				<div class="panel-body">
					<p class="text-sm">Choose a service and the number of occurrences for that service. If there are multiple additional services, you can use the add button at the bottom to create more rows as needed.</p>

					<div class="data-table data-table-bordered data-table-striped" id="serviceDataTable">
						<div class="row">
							<div class="col-lg-6">
								<div class="form-group">
									<label class="control-label">Service</label>
									{{ Form::select('additional_service[]', $services, null, array('class' => 'form-control')) }}
								</div>
							</div>
							<div class="col-lg-4">
								<div class="form-group">
									<label class="control-label">Occurrences</label>
									{{ Form::text('additional_service_occurrences[]', null, array('class' => 'form-control')) }}
								</div>
							</div>
						</div>
					</div>

					<div class="visible-lg visible-md"><a href="#" class="btn btn-sm btn-default icn-size-16 js-addService-action">{{ $_icons['add'] }}</a></div>

					<div class="visible-sm visible-xs"><a href="#" class="btn btn-lg btn-block btn-default icn-size-16 js-addService-action">{{ $_icons['add'] }}</a></div>
				</div>
			</div>
		</div>
	</div>

	{{ Form::hidden('user_limit', 1) }}
@endsection