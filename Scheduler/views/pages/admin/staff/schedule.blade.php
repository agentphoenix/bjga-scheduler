@extends('layouts.master')

@section('title')
	Staff Schedule
@stop

@section('content')
	<h1>Schedule <small>{{ $staff->user->name }}</small></h1>

	<div class="visible-lg">
		<div class="btn-toolbar">
			<div class="btn-group">
				<a href="{{ URL::route('admin.staff.index') }}" class="btn btn-sm btn-default icn-size-16">{{ $_icons['back'] }}</a>
			</div>
		</div>
	</div>
	<div class="hidden-lg">
		<div class="row">
			<div class="col-xs-6 col-sm-6">
				<p><a href="{{ URL::route('admin.staff.index') }}" class="btn btn-block btn-lg btn-default icn-size-16">{{ $_icons['back'] }}</a></p>
			</div>
		</div>
	</div>

	@if ($schedule->count() > 0)
		<div class="data-table data-table-striped data-table-bordered">
		@foreach ($schedule as $s)
			<div class="row">
				<div class="col-xs-12 col-sm-8 col-lg-8">
					<h3>
						@if ($s->service->isLesson())
							{{ $s->userAppointments->first()->user->name }} <small>{{ $s->service->name }}</small>
						@else
							{{ $s->service->name }}
						@endif
					</h3>
				</div>
				<div class="col-xs-12 col-sm-4 col-lg-4">
					<h4>{{ $s->start->format('l, M jS, Y') }}</h4>
					<h4>{{ $s->start->format('g:ia') }} - {{ $s->end->format('g:ia') }}</h4>
				</div>
			</div>
		@endforeach
		</div>
	@else
		{{ partial('common/alert', array('content' => 'No appointments found.')) }}
	@endif
@stop