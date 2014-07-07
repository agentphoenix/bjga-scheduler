@extends('layouts.master')

@section('title')
	Edit Program Service
@stop

@section('content')
	<h1>Edit Program Service <small>{{ $service->name }}</small></h1>

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

	{{ Form::model($service, array('route' => array('admin.service.update', $service->id), 'method' => 'put')) }}
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
			<div class="col-lg-4">
				<div class="form-group{{ ($errors->has('slug')) ? ' has-error' : '' }}">
					<label class="control-label">Slug</label>
					{{ Form::text('slug', null, array('class' => 'form-control input-with-feedback')) }}
					{{ $errors->first('slug', '<p class="help-block">:message</p>') }}
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
				<p class="help-block">If the service is free, enter zero. Enter the price <em>per instance</em>.</p>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-2">
				<label class="control-label">User Limit</label>
				{{ Form::text('user_limit', null, array('class' => 'form-control')) }}
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
						<label class="radio-inline text-sm">{{ Form::radio('status', (int) true) }} Active</label>
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
						<label class="radio-inline text-sm">{{ Form::radio('loyalty', (int) false) }} No</label>
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
							@foreach ($schedule as $s)
								<div class="row">
									<div class="col-lg-3">
										<div class="form-group">
											<label class="control-label">Date</label>
											{{ Form::text('service_dates['.$s->id.']', $s->start->format('Y-m-d'), array('class' => 'form-control js-datepicker')) }}
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label class="control-label">Start Time</label>
											{{ Form::text('service_times_start['.$s->id.']', $s->start->format('H:i A'), array('class' => 'form-control js-timepicker')) }}
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label class="control-label">End Time</label>
											{{ Form::text('service_times_end['.$s->id.']', $s->end->format('H:i A'), array('class' => 'form-control js-timepicker')) }}
										</div>
									</div>
									<div class="col-lg-1">
										<div class="visible-xs visible-sm">
											<p><a class="btn btn-lg btn-block btn-danger icn-size-16 js-removeSchedule-action" data-id="{{ $s->id }}">{{ $_icons['remove'] }}</a></p>
										</div>
										<div class="visible-md visible-lg">
											<label class="control-label">&nbsp;</label>
											<a class="btn btn-xs btn-danger icn-size-16 js-removeSchedule-action" data-id="{{ $s->id }}">{{ $_icons['remove'] }}</a>
										</div>
									</div>
								</div>
							@endforeach
							<div class="row">
								<div class="col-lg-3">
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
						container: '.container-fluid',
						editable: true
					});
				}
				else
				{
					$(this).pickatime({
						format: "HH:i A",
						interval: 15,
						min: [7, 0],
						max: [21, 0],
						container: '.container-fluid',
						editable: true
					});
				}
			}).end().appendTo('#serviceScheduleTable');
		});

		$(document).on('click', '.js-removeSchedule-action', function(e)
		{
			e.preventDefault();

			var row = $(this).closest('.row');

			$.ajax({
				data: { 'id': $(this).data('id') },
				url: "{{ URL::route('ajax.removeServiceScheduleItem') }}",
				type: "POST",
				success: function(data)
				{
					var obj = $.parseJSON(data);

					if (obj.code == 1)
					{
						row.fadeOut(300, function()
						{
							$(this).remove();
						});
					}
				}
			});
		});

		$(function()
		{
			$('.js-timepicker').each(function()
			{
				$(this).pickatime({
					format: "HH:i A",
					interval: 15,
					min: [7, 0],
					max: [21, 0],
					container: '.container-fluid',
					editable: true
				});
			});

			$('.js-datepicker').pickadate({
				format: "yyyy-mm-dd",
				max: false,
				container: '.container-fluid',
				editable: true
			});
		});
	</script>
@stop