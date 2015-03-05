@extends('layouts.master')

@section('title')
	Locations
@stop

@section('content')
	<h1>Locations</h1>

	<div class="visible-md visible-lg">
		<div class="btn-toolbar">
			<div class="btn-group">
				<a href="{{ route('admin.locations.create') }}" class="btn btn-sm btn-primary icn-size-16">{{ $_icons['add'] }}</a>
			</div>
		</div>
	</div>
	<div class="visible-xs visible-sm">
		<div class="row">
			<div class="col-xs-6 col-sm-3">
				<p><a href="{{ route('admin.locations.create') }}" class="btn btn-block btn-lg btn-primary icn-size-16">{{ $_icons['add'] }}</a></p>
			</div>
		</div>
	</div>

	@if ($locations->count() > 0)
		<div class="data-table data-table-striped data-table-bordered">
		@foreach ($locations as $location)
			<div class="row">
				<div class="col-sm-6 col-md-9">
					<p><strong>{{ $location->present()->name }}</strong></p>
				</div>

				<div class="col-sm-6 col-md-3">
					<div class="visible-md visible-lg">
						<div class="btn-toolbar pull-right">
							<div class="btn-group">
								<a href="{{ route('admin.locations.edit', array($location->id)) }}" class="btn btn-sm btn-default icn-size-16 js-tooltip-top" data-title="Edit Location">{{ $_icons['edit'] }}</a>
							</div>

							<div class="btn-group">
								<a href="#" class="btn btn-sm btn-danger icn-size-16 js-location-action js-tooltip-top" data-title="Delete Location" data-action="delete" data-id="{{ $location->id }}">{{ $_icons['remove'] }}</a>
							</div>
						</div>
					</div>
					<div class="visible-xs visible-sm">
						<div class="row">
							<div class="col-sm-6">
								<p><a href="{{ route('admin.locations.edit', array($location->id)) }}" class="btn btn-block btn-lg btn-default icn-size-16">Edit Location</a></p>
							</div>
							
							<div class="col-sm-6">
								<p><a href="#" class="btn btn-block btn-lg btn-danger icn-size-16 js-location-action" data-action="delete" data-id="{{ $location->id }}">Delete Location</a></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		@endforeach
		</div>
	@else
		{{ partial('common/alert', ['type' => 'warning', 'content' => "No locations found."]) }}
	@endif
@stop

@section('modals')
	{{ modal(['id' => 'deleteLocation', 'header' => "Delete Location"]) }}
@stop

@section('scripts')
	<script>
		$(document).on('click', '.js-location-action', function(e)
		{
			e.preventDefault();

			var action = $(this).data('action');
			var id = $(this).data('id');

			if (action == 'delete')
			{
				$('#deleteLocation').modal({
					remote: "{{ URL::to('admin/locations/delete') }}/" + id
				}).modal('show');
			}
		});
	</script>
@stop