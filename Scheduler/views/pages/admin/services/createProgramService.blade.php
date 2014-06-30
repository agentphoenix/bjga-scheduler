@extends('layouts.master')

@section('title')
	Create Program Service
@stop

@section('content')
	<h1>Create Program Service</h1>

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
				<div class="col-xs-6 col-sm-3">
					<p><a href="{{ URL::route('admin.service.index') }}" class="btn btn-block btn-lg btn-default icn-size-16">{{ $_icons['back'] }}</a></p>
				</div>
			</div>
		</div>
	@endif

	{{ Form::open(array('route' => 'admin.service.store')) }}
		<div class="row">
			<div class="col-lg-4">
				<div class="form-group{{ ($errors->has('staff_id')) ? ' has-error' : '' }}">
					<label class="control-label">Instructor</label>
					{{ Form::select('staff_id', $staff, null, array('class' => 'form-control')) }}
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
					{{ Form::textarea('description', null, array('class' => 'form-control', 'rows' => 8)) }}
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

		<div class="row">
			<div class="col-lg-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h2 class="panel-title">Service Schedule</h2>
					</div>
					<div class="panel-body">
						<p class="text-sm">Set all the dates and times this service will occur. When a student signs up for the service, their schedule will automatically be created with all of its dates.</p>

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

@section('styles')
	{{ HTML::style('css/picker.default.css') }}
	{{ HTML::style('css/picker.default.date.css') }}
	{{ HTML::style('css/picker.default.time.css') }}
@stop

@section('scripts')
	{{ HTML::script('js/picker.js') }}
	{{ HTML::script('js/picker.date.js') }}
	{{ HTML::script('js/picker.time.js') }}
	{{ HTML::script('js/picker.legacy.js') }}
	<script>
		$(document).on('click', '.js-addSchedule-action', function(e)
		{
			e.preventDefault();

			$('#serviceScheduleTable .row:first').clone().find("input").each(function()
			{
				if ($(this).hasClass('js-datepicker'))
				{
					$(this).val('').pickadate({
						format: "yyyy-mm-dd",
						max: false,
						container: '.container-fluid'
					});
				}
				else
				{
					$(this).pickatime({
						format: "HH:i A",
						interval: 15,
						min: [7, 0],
						max: [21, 0],
						container: '.container-fluid'
					});
				}
			}).end().appendTo('#serviceScheduleTable');
		});

		$(function()
		{
			$('.js-datepicker').pickadate({
				format: "yyyy-mm-dd",
				max: false,
				container: '.container-fluid',
			});

			$('.js-timepicker').pickatime({
					format: "HH:i A",
					interval: 15,
					min: [7, 0],
					max: [21, 0],
					container: '.container-fluid'
				});
		});
	</script>
@stop