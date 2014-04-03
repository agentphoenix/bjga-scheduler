@extends('layouts.ajax')

@section('content')
	<div class="row">
		<div class="col-lg-12">
			<div class="form-group">
				<label class="control-label">Price</label>
				<h3 class="text-success price-details">{{ $service->present()->price }}</h3>
			</div>
		</div>
	</div>
@stop