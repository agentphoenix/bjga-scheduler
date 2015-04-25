@extends('layouts.master')

@section('title')
	Services
@stop

@section('content')
	<h1>Services</h1>

	<div class="visible-md visible-lg">
		<div class="btn-toolbar">
			<div class="btn-group">
				<button type="button" class="btn btn-sm btn-primary icn-size-16 dropdown-toggle" data-toggle="dropdown">{{ $_icons['add'] }}</button>
				<ul class="dropdown-menu" role="menu">
					<li><a href="{{ URL::route('admin.service.createLesson') }}">New Lesson Service</a></li>
					<li><a href="{{ URL::route('admin.service.createProgram') }}">New Program Service</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="visible-xs visible-sm">
		<div class="row">
			<div class="col-xs-6 col-sm-3">
				<p>
					<button type="button" class="btn btn-block btn-lg btn-primary icn-size-16 dropdown-toggle" data-toggle="dropdown">{{ $_icons['add'] }}</button>
					<ul class="dropdown-menu" role="menu">
						<li><a href="{{ URL::route('admin.service.createLesson') }}">New Lesson Service</a></li>
						<li><a href="{{ URL::route('admin.service.createProgram') }}">New Program Service</a></li>
					</ul>
				</p>
			</div>
		</div>
	</div>

	<ul class="nav nav-tabs">
		<li><a href="#lesson" data-toggle="tab">Lessons</a></li>
		<li><a href="#program" data-toggle="tab">Programs</a></li>
	</ul>

	<div class="tab-content">
	@foreach ($categories as $category)
		<div class="tab-pane" id="{{ $category }}">

		@if (count($services[$category]) > 0)
			<div class="data-table data-table-striped data-table-bordered sortable">
			@foreach ($services[$category] as $service)
				<div class="row" id="service_{{ $service->id }}">
					<div class="col-xs-12 col-sm-6 col-md-9 col-lg-9">
						<p>
							<strong>{{ $service->name }}</strong>
							@if ((bool) $service->status === false)
								<span class="label label-warning">Inactive</span>
							@endif
						</p>
						@if ($_currentUser->access() >= 3)
							<p class="text-sm">{{ $service->staff->user->name }}</p>
						@endif
					</div>
					<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
						<div class="visible-md visible-lg">
							<div class="btn-toolbar pull-right">
								@if ($service->isProgram())
									<div class="btn-group">
										<a href="{{ URL::route('admin.appointment.attendees', array('service', $service->id)) }}" class="btn btn-sm btn-default icn-size-16 js-tooltip-top" data-title="View All Attendees" data-toggle="modal" data-target="#serviceAttendees">{{ $_icons['users'] }}</a>
									</div>
								@endif

								<div class="btn-group">
									<a href="{{ URL::route('admin.service.edit', array($service->id)) }}" class="btn btn-sm btn-default icn-size-16 js-tooltip-top" data-title="Edit Service">{{ $_icons['edit'] }}</a>
								</div>

								<div class="btn-group">
									<a href="{{ URL::route('admin.service.destroy', array($service->id)) }}" class="btn btn-sm btn-danger icn-size-16 js-service-action js-tooltip-top" data-title="Remove Service" data-action="delete" data-id="{{ $service->id }}">{{ $_icons['remove'] }}</a>
								</div>
							</div>
						</div>
						<div class="visible-xs visible-sm">
							<div class="row">
								<div class="col-xs-12 col-sm-4">
									@if ($service->isProgram())
										<p><a class="btn btn-lg btn-block btn-default icn-size-16 js-service-action" data-action="attendees" data-id="{{ $service->id }}">View All Attendees</a></p>
									@else
										<div class="visible-sm">&nbsp;</div>
									@endif
								</div>

								<div class="col-xs-12 col-sm-4">
									<p><a href="{{ URL::route('admin.service.edit', array($service->id)) }}" class="btn btn-block btn-lg btn-default icn-size-16">Edit Service</a></p>
								</div>

								<div class="col-xs-12 col-sm-4">
									<p><a href="{{ URL::route('admin.service.destroy', array($service->id)) }}" class="btn btn-block btn-lg btn-danger icn-size-16 js-service-action" data-action="delete" data-id="{{ $service->id }}">Remove Service</a></p>
								</div>
							</div>
						</div>
					</div>
				</div>
			@endforeach
			</div>
		@else
			<div class="alert alert-warning">No {{ $category }} services found.</div>
		@endif
		</div>
	@endforeach
	</div>
@stop

@section('modals')
	{{ modal(array('id' => 'deleteService', 'header' => "Delete Service")) }}
	{{ modal(array('id' => 'serviceAttendees', 'header' => "Service Attendees")) }}
@stop

@section('scripts')
	{{ HTML::script('js/jquery.ui.core.min.js') }}
	{{ HTML::script('js/jquery.ui.widget.min.js') }}
	{{ HTML::script('js/jquery.ui.mouse.min.js') }}
	{{ HTML::script('js/jquery.ui.sortable.min.js') }}
	<script>

		$(document).ready(function()
		{
			$('.nav-tabs a:first').tab('show');

			// Makes the list sortable and update when the sort stops
			$('.sortable').sortable({
				stop: function(event, ui)
				{
					$.ajax({
						type: 'POST',
						url: "{{ URL::route('ajax.reorderService') }}",
						data: $(this).sortable('serialize')
					});
				}
			}).disableSelection();
		});
		
		$(document).on('click', '.js-service-action', function(e)
		{
			e.preventDefault();

			var action = $(this).data('action');
			var id = $(this).data('id');

			if (action == 'delete')
			{
				$('#deleteService').modal({
					remote: "{{ URL::to('ajax/service/delete') }}/" + id
				}).modal('show');
			}

			if (action == 'attendees')
			{
				$('#serviceAttendees').modal({
					remote: "{{ URL::to('ajax/appointment/attendees/service') }}/" + id
				}).modal('show');
			}
		});

	</script>
@stop