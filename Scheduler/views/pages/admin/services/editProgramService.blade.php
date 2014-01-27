@extends('layouts.master')

@section('title')
	Edit Program Service
@stop

@section('content')
	<h1>Edit Program Service <small>{{ $service->name }}</small></h1>

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
				{{ Form::text('user_limit', null, array('class' => 'form-control')) }}
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
										<label class="control-label">&nbsp;</label>
										<a class="btn btn-xs btn-danger icn-size-16 js-removeSchedule-action" data-id="{{ $s->id }}">{{ $_icons['remove'] }}</a>
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
	<script src="{{ URL::asset('js/bootstrap-datetimepicker.min.js') }}"></script>
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
						format: "YYYY-MM-DD",
						minuteStepping: 15
					});
				}
				else
				{
					$(this).datetimepicker({
						pickDate: false,
						format: "HH:mm A",
						minuteStepping: 15
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
				$(this).datetimepicker({
					pickDate: false,
					format: "HH:mm A",
					minuteStepping: 15,
					defaultDate: moment($(this).val(), "HH:mm:ss")
				});
			});

			$('.js-datepicker').datetimepicker({
				pickTime: false,
				format: "YYYY-MM-DD",
				minuteStepping: 15
			});

			$('.js-timepicker').datetimepicker({
				pickDate: false,
				format: "HH:mm A",
				minuteStepping: 15
			});
		});

	</script>
@stop