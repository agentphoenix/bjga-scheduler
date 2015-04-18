@extends('layouts.master')

@section('title')
	Locations
@stop

@section('content')
	<h1>Locations</h1>

	@if ($locations->count() > 0)
		<div class="row">
		@foreach ($locations as $location)
			<div class="col-sm-6 col-md-4">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h2 class="panel-title">{{ $location->present()->name }}</h2>
					</div>
					<div class="panel-body">
						{{ $location->present()->address }}
						<p>{{ $location->present()->phone }}</p>

						<p><a href="{{ $location->present()->url }}" target="_blank" class="btn btn-lg btn-block btn-default">Website</a></p>

						<p><a href="http://maps.google.com/?q={{ $location->address }}" target="_blank" class="btn btn-lg btn-block btn-default">Get Directions</a></p>

						<p class="visible-xs"><a href="tel:{{ $location->phone }}" class="btn btn-block btn-lg btn-default">Call Location</a></p>
					</div>
				</div>
			</div>
		@endforeach
		</div>
	@else
		{{ alert('warning', "No locations found.") }}
	@endif
@stop