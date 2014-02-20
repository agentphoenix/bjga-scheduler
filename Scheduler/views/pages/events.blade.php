@extends('layouts.master')

@section('title')
	All Events
@endsection

@section('content')
	<h1>All Events</h1>

	@if (count($events) > 0)
		<dl>
		@foreach ($events as $event)
			<?php $eventDate = Date::createFromFormat('Y-m-d', $event->date);?>
			<?php $eventStart = Date::createFromFormat('H:i:s', $event->start_time);?>
			<?php $eventEnd = Date::createFromFormat('H:i:s', $event->end_time);?>
			<?php $openSlots = $event->service->user_limit - $event->userAppointments->count();?>
			<?php $hasOpenings = $event->userAppointments->count() < $event->service->user_limit;?>

			<dt>{{ $event->service->name }}</dt>
			<dd>
				@if ($eventDate->isToday())
					Today
				@elseif ($eventDate->isTomorrow())
					Tommorow
				@else
					{{ $eventDate->format('l F jS, Y') }}
				@endif
			</dd>
			<dd>{{ $eventStart->format('g:ia') }} - {{ $eventEnd->format('g:ia') }}</dd>
			
			<dd>
				<span class="label label-default label-lg">
				@if (Str::lower($event->service->price) != 'free')
					${{ $event->service->price }}
				@else
					{{ $event->service->price }}
				@endif
				</span>

				@if ($hasOpenings and ($openSlots <= 5 and $openSlots > 1))
					&nbsp;<span class="label label-warning">Only {{ $openSlots }} slots left. Enroll today!</span>
				@endif

				@if ($hasOpenings and $openSlots == 1)
					&nbsp;<span class="label label-danger">Only 1 slot left. Enroll today!</span>
				@endif
			</dd>
			
			<dd><p class="help-block">{{ $event->service->description }}</p></dd>

			<dd>
				<div class="visible-md visible-lg">
					<div class="btn-toolbar">
						<div class="btn-group">
							<a href="{{ URL::route('event', array($event->service->slug)) }}" class="btn btn-sm btn-default">More Info</a>
						</div>

						@if (Auth::check() and $hasOpenings)
							<div class="btn-group">
								<a href="#" class="btn btn-sm btn-primary">Enroll Now</a>
							</div>
						@endif

						@if (Auth::check() and $hasOpenings)
							<div class="btn-group">
								<a href="#" class="btn btn-sm btn-danger">Withdraw</a>
							</div>
						@endif
					</div>
				</div>
				<div class="visible-xs visible-sm">
					<p><a href="{{ URL::route('event', array($event->service->slug)) }}" class="btn btn-block btn-lg btn-default">More Info</a></p>

					@if (Auth::check() and $hasOpenings and ! Auth::user()->isAttending($event->id))
						<p><a href="#" class="btn btn-block btn-lg btn-primary">Enroll Now</a></p>
					@endif

					@if (Auth::check() and Auth::user()->isAttending($event->id))
						<p><a href="#" class="btn btn-block btn-lg btn-danger">Withdraw</a></p>
					@endif
				</div>
			</dd>
		@endforeach
		</dl>
	@else
		<div class="alert">
			There are no scheduled events in the next 90 days. Check back regularly for more events and programs.
		</div>
	@endif
@endsection

@section('scripts')
	<script type="text/javascript">
		
		$(document).on('click', '.js-enroll', function(e)
		{
			e.preventDefault();

			$.ajax({
				type: "POST",
				data: {
					'appointment': $(this).data('appointment')
				},
				url: "{{ URL::route('ajax.enroll') }}",
				success: function(data)
				{
					location.reload();
				}
			});
		});

		$(document).on('click', '.js-withdraw', function(e)
		{
			e.preventDefault();

			$.ajax({
				type: "POST",
				data: {
					'appointment': $(this).data('appointment')
				},
				url: "{{ URL::route('ajax.withdraw') }}",
				success: function(data)
				{
					location.reload();
				}
			});
		});

	</script>
@endsection