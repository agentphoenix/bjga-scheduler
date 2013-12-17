@extends('layouts.master')

@section('title')
	Edit Many-to-Many Service
@endsection

@section('content')
	<h1>Edit Many-to-Many Service <small>{{ $service->name }}</small></h1>

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

	{{ Form::model($service, array('route' => array('admin.service.update', $service->id), 'method' => 'put')) }}
		<div class="row">
			<div class="col-lg-4">
				<div class="form-group{{ ($errors->has('category_id')) ? ' has-error' : '' }}">
					<label class="label-control">Category</label>
					{{ Form::select('category_id', $categories, null, array('class' => 'form-control')) }}
					{{ $errors->first('category_id', '<p class="help-block">:message</p>') }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-4">
				<div class="form-group{{ ($errors->has('staff_id')) ? ' has-error' : '' }}">
					<label class="label-control">Staff Member</label>
					{{ Form::select('staff_id', $staff, null, array('class' => 'form-control')) }}
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
							@foreach ($additionalServices as $s)
								<div class="row">
									<div class="col-lg-6">
										<div class="form-group">
											<label class="control-label">Service</label>
											{{ Form::select('additional_service[]', $services, $s['service'], array('class' => 'form-control')) }}
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label class="control-label">Occurrences</label>
											{{ Form::text('additional_service_occurrences[]', $s['occurrences'], array('class' => 'form-control')) }}
										</div>
									</div>
								</div>
							@endforeach
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

		<div class="row">
			<div class="col-lg-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h2 class="panel-title">Service Schedule</h2>
					</div>
					<div class="panel-body">
						<p class="text-small">Set all the dates and times this service will occur. When a student signs up for the service, their schedule will automatically be created with all of its dates.</p>

						<div class="data-table data-table-bordered data-table-striped" id="serviceScheduleTable">
							@foreach ($schedule as $s)
								<div class="row">
									<div class="col-lg-4">
										<div class="form-group">
											<label class="control-label">Date</label>
											{{ Form::text('service_dates['.$s->id.']', $s->date, array('class' => 'form-control js-datepicker')) }}
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label class="control-label">Start Time</label>
											{{ Form::text('service_times_start['.$s->id.']', $s->start_time, array('class' => 'form-control js-timepicker')) }}
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label class="control-label">End Time</label>
											{{ Form::text('service_times_end['.$s->id.']', $s->end_time, array('class' => 'form-control js-timepicker')) }}
										</div>
									</div>
								</div>
							@endforeach
							<div class="row">
								<div class="col-lg-4">
									<div class="form-group">
										<label class="control-label">Date</label>
										{{ Form::text('service_dates[]', null, array('class' => 'form-control js-datepicker')) }}
									</div>
								</div>
								<div class="col-lg-4">
									<div class="form-group">
										<label class="control-label">Start Time</label>
										{{ Form::text('service_times_start[]', null, array('class' => 'form-control js-timepicker')) }}
									</div>
								</div>
								<div class="col-lg-4">
									<div class="form-group">
										<label class="control-label">End Time</label>
										{{ Form::text('service_times_end[]', null, array('class' => 'form-control js-timepicker')) }}
									</div>
								</div>
							</div>
						</div>

						<div class="visible-lg visible-md"><a href="#" class="btn btn-sm btn-default icn-size-16 js-addSchedule-action">{{ $_icons['add'] }}</a></div>

						<div class="visible-sm visible-xs"><a href="#" class="btn btn-lg btn-block btn-default icn-size-16 js-addSchedule-action">{{ $_icons['add'] }}</a></div>
					</div>
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

		$(document).on('click', '.js-addSchedule-action', function(e)
		{
			e.preventDefault();

			$('#serviceScheduleTable .row:first').clone().find("input").each(function()
			{
				if ($(this).hasClass('js-datepicker'))
				{
					$(this).val('').datetimepicker({
						pickTime: false,
						format: "YYYY-MM-DD"
					});
				}
				else
				{
					$(this).datetimepicker({
						pickDate: false,
						format: "HH:mm A"
					});
				}
			}).end().appendTo('#serviceScheduleTable');
		});

		$(function()
		{
			$('.js-timepicker').each(function()
			{
				$(this).datetimepicker({
					pickDate: false,
					format: "HH:mm A",
					defaultDate: moment($(this).val(), "HH:mm:ss")
				});
			});

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