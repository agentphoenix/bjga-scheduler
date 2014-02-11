@extends('layouts.master')

@section('title')
	Services
@endsection

@section('content')
	<h1>Services</h1>

	<div class="visible-lg">
		<div class="btn-toolbar">
			<div class="btn-group">
				<a href="{{ URL::route('admin') }}" class="btn btn-sm btn-default icn-size-16">{{ $_icons['back'] }}</a>
			</div>
			<div class="btn-group">
				<button type="button" class="btn btn-sm btn-primary icn-size-16 dropdown-toggle" data-toggle="dropdown">{{ $_icons['add'] }}</button>
				<ul class="dropdown-menu" role="menu">
					<li><a href="{{ URL::route('admin.service.createLesson') }}">New Lesson Service</a></li>
					<li><a href="{{ URL::route('admin.service.createProgram') }}">New Program Service</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="hidden-lg">
		<div class="row">
			<div class="col-xs-6 col-sm-6">
				<p><a href="{{ URL::route('admin') }}" class="btn btn-block btn-lg btn-default icn-size-16">{{ $_icons['back'] }}</a></p>
			</div>
			<div class="col-xs-6 col-sm-6">
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
		<div id="{{ strtolower(str_replace(' ', '', $category)) }}" class="tab-pane">
			@if (count($services[$category]) > 0)
				<div class="data-table data-table-striped data-table-bordered">
				@foreach ($services[$category] as $service)
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-lg-9">
							<p>
								<strong>{{ $service->name }}</strong>
								@if ((bool) $service->status === false)
									<span class="label label-warning">Inactive</span>
								@endif
							</p>
							<p class="text-sm text-muted">{{ $service->description }}</p>
						</div>
						<div class="col-xs-12 col-sm-12 col-lg-3">
							<div class="visible-lg">
								<div class="btn-toolbar pull-right">
									@if ($service->isProgram())
										<div class="btn-group">
											<a href="{{ URL::route('admin.appointment.show', array($service->id)) }}" class="btn btn-sm btn-default icn-size-16" data-toggle="modal" data-target="#serviceAttendees">{{ $_icons['users'] }}</a>
										</div>
									@endif

									<div class="btn-group">
										<a href="{{ URL::route('admin.service.edit', array($service->id)) }}" class="btn btn-sm btn-default icn-size-16">{{ $_icons['edit'] }}</a>
									</div>

									@if ($_currentUser->access() >= 2)
										<div class="btn-group">
											<a href="{{ URL::route('admin.service.destroy', array($service->id)) }}" class="btn btn-sm btn-danger icn-size-16 js-service-action" data-action="delete" data-id="{{ $service->id }}">{{ $_icons['remove'] }}</a>
										</div>
									@endif
								</div>
							</div>
							<div class="hidden-lg">
								<div class="row">
									@if ($service->isProgram())
										<div class="col-xs-12 col-sm-4">
											<p><a class="btn btn-lg btn-block btn-default icn-size-16 js-service-action" data-action="attendees" data-id="{{ $service->id }}">{{ $_icons['users'] }}</a></p>
										</div>
									@endif

									<div class="col-xs-12 col-sm-4">
										<p><a href="{{ URL::route('admin.service.edit', array($service->id)) }}" class="btn btn-block btn-lg btn-default icn-size-16">{{ $_icons['edit'] }}</a></p>
									</div>

									@if ($_currentUser->access() >= 2)
										<div class="col-xs-12 col-sm-4">
											<p><a href="{{ URL::route('admin.service.destroy', array($service->id)) }}" class="btn btn-block btn-lg btn-danger icn-size-16 js-service-action" data-action="delete" data-id="{{ $service->id }}">{{ $_icons['remove'] }}</a></p>
										</div>
									@endif
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

	{{ modal(array('id' => 'deleteService', 'header' => "Delete Service")) }}
	{{ modal(array('id' => 'serviceAttendees', 'header' => "Service Attendees")) }}
@endsection

@section('scripts')
	<script type="text/javascript">

		$(document).ready(function()
		{
			$('.nav-tabs a:first').tab('show');
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
					remote: "{{ URL::to('ajax/appointment/show') }}/" + id
				}).modal('show');
			}
		});

	</script>
@endsection