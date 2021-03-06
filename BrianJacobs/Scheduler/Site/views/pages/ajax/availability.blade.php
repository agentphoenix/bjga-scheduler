@extends('layouts.ajax')

@section('content')
	<?php $avail = count($availability);?>
	
	<h3>Available Times</h3>

	<div class="row">
	@foreach ($availability as $av)
		@if ($av !== false)
			<div class="col-xs-12 col-sm-4 col-md-4 col-lg-2">
				<p><a href="#" class="btn btn-lg btn-block btn-default js-book" data-date="{{ $date->format('Y-m-d') }}" data-time="{{ $av->format('G:i') }}">{{ $av->format(Config::get('bjga.dates.time')) }}</a></p>
			</div>
		@else
			<?php --$avail;?>
		@endif
	@endforeach
	</div>
	<div style="clear:both;"></div>
	
	@if ($avail === 0)
		<p class="alert alert-warning">We're sorry, but we couldn't find enough available time for that service on {{ $date->format(Config::get('bjga.dates.date')) }}. Please choose a different date and try again. You can search for available lesson times from the <a href="{{ route('search') }}">Find a Lesson Time</a> page.</p>
	@endif
@stop

@section('scripts')
	{{ HTML::script('js/moment.min.js') }}
	{{ HTML::script('js/bootstrap-datetimepicker.min.js') }}
	{{ HTML::script('js/jquery.plugin.min.js') }}
	{{ HTML::script('js/jquery.countdown.min.js') }}
@stop