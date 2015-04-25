@extends('layouts.ajax')

@section('content')
	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">
				<label class="control-label">Price</label>
				<h3 class="text-success price-details">{{ $service->present()->price }}</h3>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="form-group">
				<label class="control-label">Location</label>
				<h3 class="text-success price-details">{{ $location }}</h3>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="form-group">
				<label class="control-label">Instructor</label>
				<h3 class="text-success price-details">{{ $service->present()->staffName }}</h3>
			</div>
		</div>
	</div>
@stop