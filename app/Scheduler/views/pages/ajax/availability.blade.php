@extends('layouts.ajax')

@section('content')
	<?php $avail = count($availability);?>
	<hr>

	<h3>Available Times</h3>

	<div class="row">
	@foreach ($availability as $av)
		@if ($av !== false)
			<div class="col-lg-2">
				<p><a href="#" class="btn btn-block btn-default">{{ $av->format('g:ia') }}</a></p>
			</div>
		@else
			<?php --$avail;?>
		@endif
	@endforeach
	</div>
	<div style="clear:both;"></div>
	
	@if ($avail === 0)
		<p class="alert alert-info">We're sorry, but we couldn't find enough available time for that service on {{ $date }}. Please choose a different date.</p>
	@endif
@stop