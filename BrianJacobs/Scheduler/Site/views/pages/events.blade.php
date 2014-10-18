@extends('layouts.master')

@section('title')
	Upcoming Programs
@stop

@section('content')
	<h1>Upcoming Programs</h1>

	@if (count($events) > 0)
		<div class="row">
			<div class="col-xs-12 col-sm-12 visible-xs visible-sm">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Icon Key</h3>
					</div>
					<div class="panel-body">
						<p>
							<span class="label label-default icn-size-16">{{ $_icons['calendar'] }}</span>&nbsp;
							<span class="text-muted text-sm">Program spans multiple days</span>
						</p>
						<p>
							<span class="label label-warning icn-size-16">{{ $_icons['warning'] }}</span>&nbsp;
							<span class="text-muted text-sm">Space limited, enroll today</span>
						</p>
						<p>
							<span class="label label-success icn-size-16">{{ $_icons['check'] }}</span>&nbsp;
							<span class="text-muted text-sm">You are currently enrolled</span>
						</p>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
		@foreach ($months as $month)
			@if (array_key_exists($month, $events))
				<div class="col-lg-12">
					<h2>{{ $month }}</h2>
				</div>

				<div class="col-lg-12">
					<div class="row">
					@foreach ($events as $event)
						<?php $appt = $event->appointments->first();?>
						<?php $openSlots = $event->user_limit - $event->attendees()->count();?>
						<?php $hasOpenings = $event->attendees()->count() < $event->user_limit;?>

						<div class="col-lg-6">
							<div class="well well-sm">
								<h3>
									{{ $event->name }}

									<small>{{ $event->present()->price }}</small>
								</h3>

								<div class="row">
									<div class="col-sm-6 col-md-6 col-lg-6">
										<p class="text-sm">
											<strong>
												@if ($appt->start->isToday())
													Today
												@elseif ($appt->start->isTomorrow())
													Tomorrow
												@else
													{{ $appt->start->format(Config::get('bjga.dates.date')) }}
												@endif
											</strong>
											
											@if ($event->occurrences > 1)
												&nbsp;<span class="label label-default icn-size-16 js-tooltip-bottom" data-title="Program spans multiple days">{{ $_icons['calendar'] }}</span>
											@endif

											@if ($hasOpenings and ($openSlots <= 5 and $openSlots > 0))
												&nbsp; <span class="label label-warning icn-size-16 js-tooltip-bottom" data-title="Space limited, enroll today">{{ $_icons['warning'] }}</span>
											@endif

											@if (Auth::check() and $_currentUser->isAttending($event->id))
												&nbsp;<span class="label label-success icn-size-16 js-tooltip-bottom" data-title="You are currently enrolled">{{ $_icons['check'] }}</span>
											@endif

											<br>
											<span class="text-muted">{{ $appt->start->format(Config::get('bjga.dates.time')) }} - {{ $appt->end->format(Config::get('bjga.dates.time')) }}</span>
										</p>
									</div>
									<div class="col-sm-6 col-md-6 col-lg-6">
										<div class="visible-md visible-lg">
											<p class="pull-right"><a href="{{ URL::route('event', array($event->slug)) }}" class="btn btn-lg btn-default">More Info</a></p>
										</div>
										<div class="visible-xs visible-sm">
											<div class="row">
												<div class="col-sm-6 col-sm-offset-6">
													<p><a href="{{ URL::route('event', array($event->slug)) }}" class="btn btn-lg btn-block btn-default">More Info</a></p>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					@endforeach
					</div>
				</div>
			@endif
		</div>
	@else
		{{ partial('common/alert', array('class' => ' alert-warning', 'content' => "There are no scheduled events in the next 90 days. Check back regularly for more events.")) }}
	@endif
@stop