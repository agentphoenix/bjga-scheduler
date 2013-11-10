@extends('layouts.master')

@section('title')
	Create One-to-Many Service
@endsection

@section('content')
	<h1>Create One-to-Many Service</h1>

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

	<p class="alert alert-warning">A One-to-Many service is a service that has a single occurrence and multiple participants. Examples of this include events, clinics and schools.</p>

	{{ Form::open(array('route' => 'admin.service.store')) }}
		<div class="row">
			<div class="col-lg-4">
				<div class="form-group{{ ($errors->has('category_id')) ? ' has-error' : '' }}">
					<label class="label-control">Category</label>
					{{ Form::select('category_id', $categories, null, array('class' => 'form-control input-with-feedback')) }}
					{{ $errors->first('category_id', '<p class="help-block">:message</p>') }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-4">
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
					<label class="label-control">Date</label>
					{{ Form::text('date', null, array('class' => 'form-control js-datepicker')) }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-2">
				<div class="form-group">
					<label class="label-control">Start Time</label>
					{{ Form::text('start_time', null, array('class' => 'form-control js-timepicker')) }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-2">
				<div class="form-group">
					<label class="label-control">End Time</label>
					{{ Form::text('end_time', null, array('class' => 'form-control js-timepicker')) }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-2">
				<label class="label-control">User Limit</label>
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
						<p class="text-small">Choose a service and the number of occurrences for that service. If there are multiple additional services, you can use the add button at the bottom to create more rows as needed.</p>

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
										<input type="text" name="additional_service_occurrences[]" value="" class="form-control">
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

		{{ Form::hidden('slug', '') }}
		{{ Form::hidden('occurrences', 1) }}

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
@endsection

@section('scripts')
	<script src="{{ URL::asset('js/moment.min.js') }}"></script>
	<script src="{{ URL::asset('js/bootstrap-datetimepicker.js') }}"></script>
	<script>
		$(document).on('click', '.js-addService-action', function(e)
		{
			e.preventDefault();

			$('#serviceDataTable .row:first').clone().find("input").each(function()
			{
				$(this).val('');
			}).end().appendTo('#serviceDataTable');
		});

		$(function()
		{
			$('.js-datepicker').datetimepicker({
				pickTime: false,
				format: "YYYY-MM-DD"
			});

			$('.js-timepicker').datetimepicker({
				pickDate: false,
				format: "HH:mm A"
			});
		});
	</script>
@endsection