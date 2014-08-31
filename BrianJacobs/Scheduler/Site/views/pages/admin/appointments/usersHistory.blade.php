@extends('layouts.master')

@section('title')
	@if (Request::is('my-history'))
		My Appointment History
	@else
		Student Appointment History
	@endif
@stop

@section('content')
	@if (Request::is('my-history'))
		<h1>My Appointment History</h1>
	@else
		<h1>Appointment History <small>{{ $user->name }}</small></h1>

		<div class="visible-md visible-lg">
			<div class="btn-toolbar">
				<div class="btn-group">
					<a href="{{ route('admin.appointment.user') }}" class="btn btn-sm btn-default icn-size-16">{{ $_icons['back'] }}</a>
				</div>
			</div>
		</div>
		<div class="visible-xs visible-sm">
			<div class="row">
				<div class="col-xs-6 col-sm-3">
					<p><a href="{{ route('admin.appointment.user') }}" class="btn btn-lg btn-block btn-default icn-size-16">{{ $_icons['back'] }}</a></p>
				</div>
			</div>
		</div>
	@endif

	@if (count($history) > 0)
		<div class="data-table data-table-striped data-table-bordered">
		@foreach ($history as $days => $a)
			<?php $appt = $a->appointment;?>
			<div class="row">
				<div class="col-sm-4 col-md-4 col-lg-3">
					<p class="text-sm">
						<strong>{{ Date::createFromTimestamp($days)->format(Config::get('bjga.dates.date')) }}</strong><br>
						<span class="text-muted">{{ $appt->start->format(Config::get('bjga.dates.time')) }} - {{ $appt->end->format(Config::get('bjga.dates.time')) }}</span>
					</p>
				</div>
				<div class="col-sm-8 col-md-8 col-lg-9">
					<p class="lead"><strong>{{ trim($appt->service->name) }}</strong></p>
				</div>
			</div>
		@endforeach
		</div>
	@else
		{{ partial('common/alert', array('content' => "No history for {$user->name}.")) }}
	@endif
@stop