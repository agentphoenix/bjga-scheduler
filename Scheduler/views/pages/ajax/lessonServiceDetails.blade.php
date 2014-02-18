@extends('layouts.ajax')

@section('content')
	<div class="row">
		<div class="col-lg-12">
			<div class="form-group">
				<label class="control-label">Price</label>
				<h3 class="text-success price-details">
					@if ($service->price > 0)
						@if ($service->occurrences > 1)
							${{ round(($service->price * $service->occurrences) / ($service->occurrences / 4), 2) }} <small>per month</small>
						@else
							${{ $service->price }}
						@endif
					@else
						Free
					@endif
				</h3>
			</div>
		</div>
	</div>
@stop