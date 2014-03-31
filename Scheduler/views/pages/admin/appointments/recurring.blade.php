@extends('layouts.master')

@section('title')
	Recurring Lessons
@stop

@section('content')
	<h1>Recurring Lessons</h1>

	<div class="visible-md visible-lg">
		<div class="btn-toolbar">
			<div class="btn-group">
				<a href="{{ URL::route('admin.appointment.index') }}" class="btn btn-sm btn-default icn-size-16">{{ $_icons['back'] }}</a>
			</div>
		</div>
	</div>
	<div class="visible-xs visible-sm">
		<div class="row">
			<div class="col-xs-6 col-sm-3">
				<p><a href="{{ URL::route('admin.appointment.index') }}" class="btn btn-lg btn-block btn-default icn-size-16">{{ $_icons['back'] }}</a></p>
			</div>
		</div>
	</div>

	@if ($recurring->count() > 0)
		<div class="row">
			<div class="col-sm-6 col-md-6 col-lg-4">
				{{ Form::text('search', null, array('placeholder' => 'Search for appointments', 'class' => 'form-control search-control', 'id' => 'searchAppointments')) }}
			</div>
		</div>

		<div class="data-table data-table-striped data-table-bordered" id="appointmentsTable">
		@foreach ($recurring as $r)
			<div class="row">
				<div class="col-sm-6 col-md-6 col-lg-4">
					<p><strong>{{ $r->present()->userName }}</strong></p>
					<p class="text-muted text-sm">{{ $r->present()->serviceName }}</p>
				</div>
				<div class="col-sm-6 col-md-6 col-lg-4">
					<p class="text-sm">{{ $r->present()->startDate }}</p>
					<p class="text-sm">{{ $r->present()->endDate }}</p>
				</div>
				<div class="col-sm-12 col-md-6 col-lg-4">
					<div class="visible-md visible-lg">
						<div class="btn-toolbar pull-right">
							<div class="btn-group">
								<a href="{{ URL::route('admin.appointment.recurring.edit', array($r->id)) }}" class="btn btn-sm btn-default icn-size-16">{{ $_icons['edit'] }}</a>
							</div>
						</div>
					</div>
					<div class="visible-xs visible-sm">
						<div class="row">
							<div class="col-sm-3">
								<p><a href="{{ URL::route('admin.appointment.recurring.edit', array($r->id)) }}" class="btn btn-lg btn-block btn-default icn-size-16">{{ $_icons['edit'] }}</a></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		@endforeach
		</div>
	@else
		{{ partial('common/alert', array('content' => "No recurring lessons found")) }}
	@endif
@stop

@section('scripts')
	{{ HTML::script('js/jquery.quicksearch.min.js') }}
	<script>

		$('#searchAppointments').quicksearch('#appointmentsTable > div', {
			hide: function()
			{
				$(this).addClass('hide');
			},
			show: function()
			{
				$(this).removeClass('hide');
			}
		});

	</script>
@stop