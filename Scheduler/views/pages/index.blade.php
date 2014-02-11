@extends('layouts.master')

@section('title')
	My Schedule
@stop

@section('content')
	<h1>My Schedule</h1>

	@if (count($myEvents) > 0)
		<div class="data-table data-table-striped data-table-bordered">
		@foreach ($myEvents as $mine)
			<div class="row">
				<div class="col-lg-9">
					<p class="lead"><strong>{{ $mine->appointment->service->name }}</strong></p>
					<p>
						@if ($mine->appointment->start->isToday())
							Today
						@elseif ($mine->appointment->start->isTomorrow())
							Tommorow
						@else
							{{ $mine->appointment->start->format('l F jS, Y') }}
						@endif

						at {{ $mine->appointment->start->format('g:ia') }}
					</p>
				</div>
				<div class="col-lg-3">
					<div class="visible-lg">
						<div class="btn-toolbar pull-right">
							@if ($mine->appointment->service->isProgram())
								<div class="btn-group">
									<a href="#" class="btn btn-sm btn-default">More Info</a>
								</div>
							@endif
							<div class="btn-group">
								<a href="#" class="btn btn-sm btn-danger icn-size-16 js-withdraw" data-appointment="{{ $mine->id }}">{{ $_icons['remove'] }}</a>
							</div>
						</div>
					</div>
					<div class="hidden-lg">
						@if ($mine->appointment->service->isProgram())
							<p><a href="#" class="btn btn-block btn-lg btn-default">More Info</a></p>
						@endif

						<p><a href="#" class="btn btn-block btn-lg btn-danger js-withdraw" data-appointment="{{ $mine->id }}">{{ $_icons['remove'] }}</a></p>
					</div>
				</div>
			</div>
		@endforeach
		</div>
	@else
		{{ partial('common/alert', array('content' => "No upcoming appointments.")) }}
	@endif
@stop