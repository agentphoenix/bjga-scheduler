@extends('layouts.master')

@section('title')
	Find a Lesson Time
@stop

@section('content')
	<h1>Find a Lesson Time</h1>

	{{ Form::open(['route' => 'search.do']) }}
		<div class="form-group">
			<div class="row">
				<div class="col-md-3">
					<label class="control-label">Instructor</label>
					{{ Form::select('instructor', $instructors, null, ['class' => 'form-control']) }}
				</div>
				<div class="col-md-4">
					<label class="control-label">Lesson Type</label>
					{{ Form::select('duration', $lessons, null, ['class' => 'form-control']) }}
				</div>
				<div class="col-md-3 col-lg-2">
					<label class="control-label">Timeframe</label>
					{{ Form::select('timeframe', $timeframe, null, ['class' => 'form-control']) }}
				</div>
				<div class="col-md-2 col-lg-3">
					<div class="visible-xs visible-sm">
						<br>{{ Form::button("Search", ['type' => 'submit', 'class' => 'btn btn-primary btn-lg btn-block']) }}
					</div>
					<div class="visible-md visible-lg">
						<label class="control-label">&nbsp;</label>
						{{ Form::button("Search", ['type' => 'submit', 'class' => 'btn btn-primary btn-block']) }}
					</div>
				</div>
			</div>
		</div>
	{{ Form::close() }}

	@if (isset($results))
		<hr>

		@if (isset($availability))
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">{{ $header }}, {{ $lessons[Input::get('duration')] }}, {{ $timeframe[Input::get('timeframe')] }}</h3>
				</div>
				<div class="panel-body">
					@if (count($availability) > 0)
						@foreach ($availability as $a)
							<?php $hasAvailability = false;?>

							@if (is_array($a))
								<h4>{{ $a['date']->format(Config::get('bjga.dates.date')) }}</h4>

								@foreach ($a['times'] as $time)
									@if ($time)
										<?php $hasAvailability = true;?>
										{{ partial('common.label', ['class' => 'label-info', 'content' => $time->format(Config::get('bjga.dates.time'))]) }}
									@endif
								@endforeach

								@if ( ! $hasAvailability)
									{{ partial('common.label', ['class' => 'label-default', 'content' => "No availability"]) }}
								@endif
							@else
								@if ($a)
									<?php $hasAvailability = true;?>
									{{ partial('common.label', ['class' => 'label-info', 'content' => $a->format(Config::get('bjga.dates.time'))]) }}
								@endif
							@endif
						@endforeach

						@if ( ! $hasAvailability and ! is_array($a))
							{{ partial('common.label', ['class' => 'label-default', 'content' => "No availability"]) }}
						@endif
					@else
						{{ partial('common.label', ['class' => 'label-default', 'content' => "No availability"]) }}
					@endif
				</div>
			</div>
		@endif

		@if (isset($allStaffAvailability))
			@foreach ($allStaffAvailability as $availability)
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">{{ $availability['staff'] }}, {{ $lessons[Input::get('duration')] }}, {{ $timeframe[Input::get('timeframe')] }}</h3>
					</div>
					<div class="panel-body">
						@if (count($availability['times']) > 0)
							@foreach ($availability['times'] as $a)
								<?php $hasAvailability = false;?>

								@if (is_array($a))
									<h4>{{ $a['date']->format(Config::get('bjga.dates.date')) }}</h4>
									@foreach ($a['times'] as $time)
										@if ($time)
											<?php $hasAvailability = true;?>
											{{ partial('common.label', ['class' => 'label-info', 'content' => $time->format(Config::get('bjga.dates.time'))]) }}
										@endif
									@endforeach

									@if ( ! $hasAvailability)
										{{ partial('common.label', ['class' => 'label-default', 'content' => "No availability"]) }}
									@endif
								@else
									@if ($a)
										<?php $hasAvailability = true;?>
										{{ partial('common.label', ['class' => 'label-info', 'content' => $a->format(Config::get('bjga.dates.time'))]) }}
									@endif
								@endif
							@endforeach

							@if ( ! $hasAvailability and ! is_array($a))
								{{ partial('common.label', ['class' => 'label-default', 'content' => "No availability"]) }}
							@endif
						@else
							{{ partial('common.label', ['class' => 'label-default', 'content' => "No availability"]) }}
						@endif
					</div>
				</div>
			@endforeach
		@endif
	@endif
@stop