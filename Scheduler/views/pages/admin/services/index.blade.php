@extends('layouts.master')

@section('title')
	Services
@endsection

@section('content')
	<h1>Services</h1>

	<div class="visible-lg">
		<div class="btn-toolbar">
			<div class="btn-group">
				<a href="{{ URL::route('admin') }}" class="btn btn-default icn-size-16">{{ $_icons['back'] }}</a>
			</div>
			<div class="btn-group">
				<button type="button" class="btn btn-primary icn-size-16 dropdown-toggle" data-toggle="dropdown">{{ $_icons['add'] }}</button>
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
							<p><strong>{{ $service->name }}</strong></p>
							<p class="text-small text-muted">{{ $service->description }}</p>
						</div>
						<div class="col-xs-12 col-sm-12 col-lg-3">
							<div class="visible-lg">
								<div class="btn-toolbar pull-right">
									<div class="btn-group">
										<a href="{{ URL::route('admin.service.edit', array($service->id)) }}" class="btn btn-small btn-default icn-size-16">{{ $_icons['edit'] }}</a>
									</div>

									@if ($_currentUser->access() == 3)
										<div class="btn-group">
											<a href="{{ URL::route('admin.service.destroy', array($service->id)) }}" class="btn btn-small btn-danger icn-size-16 js-service-action" data-action="delete" data-id="{{ $service->id }}">{{ $_icons['remove'] }}</a>
										</div>
									@endif
								</div>
							</div>
							<div class="hidden-lg">
								<div class="row">
									<div class="col-xs-6 col-sm-4">
										<p><a href="{{ URL::route('admin.service.edit', array($service->id)) }}" class="btn btn-block btn-lg btn-default icn-size-16">{{ $_icons['edit'] }}</a></p>
									</div>

									@if ($_currentUser->access() == 3)
										<div class="col-xs-6 col-sm-4">
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
		});

	</script>
@endsection