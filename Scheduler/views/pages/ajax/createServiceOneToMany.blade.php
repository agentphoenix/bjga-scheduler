@extends('layouts.ajax')

@section('content')
	<hr>
	
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
				<label class="control-label">Date</label>
				{{ Form::text('date', null, array('class' => 'form-control js-datepicker')) }}
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-2">
			<div class="form-group">
				<label class="control-label">Start Time</label>
				{{ Form::text('start_time', null, array('class' => 'form-control')) }}
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-2">
			<div class="form-group">
				<label class="control-label">End Time</label>
				{{ Form::text('end_time', null, array('class' => 'form-control')) }}
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-2">
			<label class="control-label">User Limit</label>
			{{ Form::text('user_limit', 5, array('class' => 'form-control')) }}
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<p class="help-block">What is the maximum number of people who can attend?</p>
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

	{{ Form::hidden('occurrences', 1) }}
@endsection