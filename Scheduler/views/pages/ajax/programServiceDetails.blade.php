@extends('layouts.ajax')

@section('content')
	<div class="row">
		<div class="col-lg-4">
			<div class="form-group">
				<label class="control-label">Price</label>
				<h3 class="text-success price-details">
					@if ($price > 0)
						${{ $price }}
					@else
						Free
					@endif
				</h3>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-4">
			<div class="form-group">
				<label class="control-label">Date(s)</label>
				<div class="controls">
					<dl>
					@foreach ($dates as $date)
						<dt>{{ $date->start->format('l F jS, Y') }}</dt>
						<dd>{{ $date->start->format('g:ia') }} - {{ $date->end->format('g:ia') }}</dd>
					@endforeach
					</dl>
				</div>
			</div>
		</div>
	</div>
@stop