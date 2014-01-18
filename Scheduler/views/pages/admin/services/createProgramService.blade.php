@extends('layouts.master')

@section('title')
	Create Program Service
@stop

@section('content')
	<h1>Create Program Service</h1>

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

	{{ Form::open(array('route' => 'admin.service.store')) }}
		<div class="row">
			<div class="col-lg-4">
				<div class="form-group{{ ($errors->has('staff_id')) ? ' has-error' : '' }}">
					<label class="label-control">Instructor</label>
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
						<h2 class="panel-title">Service Schedule</h2>
					</div>
					<div class="panel-body">
						<p class="text-small">Set all the dates and times this service will occur. When a student signs up for the service, their schedule will automatically be created with all of its dates.</p>

						<div class="data-table data-table-bordered data-table-striped" id="serviceScheduleTable">
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

		{{ Form::hidden('slug', '') }}
		{{ Form::hidden('category', 'program') }}

		<div class="row">
			<div class="col-lg-12">
				<div class="visible-lg">
					{{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}
				</div>
				<div class="hidden-lg">
					{{ Form::submit('Submit', array('class' => 'btn btn-lg btn-block btn-primary')) }}
				</div>
			</div>
		</div>
	{{ Form::close() }}
@stop

@section('scripts')
	<script src="{{ URL::asset('js/moment.min.js') }}"></script>
	<script src="{{ URL::asset('js/bootstrap-datetimepicker.js') }}"></script>
	<script>
		
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
@stop