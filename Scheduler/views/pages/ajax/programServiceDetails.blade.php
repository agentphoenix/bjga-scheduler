@extends('layouts.ajax')

@section('content')
	<div class="row">
		<div class="col-lg-4">
			<div class="form-group">
				<label class="control-label">Price</label>
				<div class="controls">
					@if ($price > 0)
						${{ $price }}
					@else
						Free
					@endif
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-4">
			<div class="form-group">
				<label class="control-label">Date(s)</label>
				<div class="controls">
					@foreach ($dates as $date)
						<p>
							<strong>{{ $date->start->format('l F jS, Y') }}</strong><br>
							{{ $date->start->format('g:ia') }} - {{ $date->end->format('g:ia') }}
						</p>
					@endforeach
					</dl>
				</div>
			</div>
		</div>
	</div>
@stop