@extends('layouts.master')

@section('title')
	Report Center - Unpaid Services
@stop

@section('content')
	<h1>Unpaid Services</h1>

	<div class="visible-md visible-lg">
		<div class="btn-toolbar">
			<div class="btn-group">
				<a href="{{ URL::route('admin.reports.index') }}" class="btn btn-sm btn-default icn-size-16">{{ $_icons['back'] }}</a>
			</div>
		</div>
	</div>
	<div class="visible-xs visible-sm">
		<div class="row">
			<div class="col-xs-12 col-sm-3">
				<p><a href="{{ URL::route('admin.reports.index') }}" class="btn btn-lg btn-block btn-default icn-size-16">{{ $_icons['back'] }}</a></p>
			</div>
		</div>
	</div>

	@if ($unpaid->count() > 0)
		<h2 class="text-danger">${{ number_format($amount, 2) }} <small>in unpaid services</small></h2>

		<div class="data-table data-table-striped data-table-bordered">
		@foreach ($unpaid as $u)
			<div class="row">
				<div class="col-xs-12 col-sm-8 col-md-5 col-lg-5">
					<p><strong>{{ $u->user->name }}</strong></p>
					<p class="text-danger"><strong>${{ $u->amount }}</strong></p>
				</div>
				<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
					<p class="text-muted text-sm">{{ $u->appointment->service->name }}</p>
					<p class="text-muted text-sm">{{ $u->appointment->start->format(Config::get('bjga.dates.long')) }}
				</div>
				<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<div class="visible-md visible-lg">
						<div class="btn-toolbar pull-right">
							<div class="btn-group">
								<a href="#" class="btn btn-sm btn-default icn-size-16 js-email" data-user="{{ $u->user->id }}">{{ $_icons['email'] }}</a>
							</div>
							<div class="btn-group">
								<a href="#" class="btn btn-sm btn-primary icn-size-16 js-markAsPaid" data-appt="{{ $u->id }}">{{ $_icons['check'] }}</a>
							</div>
						</div>
					</div>
					<div class="visible-xs visible-sm">
						<div class="row">
							<div class="col-xs-12 col-sm-4">
								<p><a href="#" class="btn btn-lg btn-block btn-default icn-size-16 js-email" data-user="{{ $u->user->id }}">{{ $_icons['email'] }}</a></p>
							</div>
							<div class="col-xs-12 visible-xs">
								<p><a href="tel:{{ $u->user->phone }}" class="btn btn-block btn-lg btn-default icn-size-16">{{ $_icons['phone'] }}</a></p>
							</div>
							<div class="col-xs-12 col-sm-4">
								<p><a href="#" class="btn btn-block btn-lg btn-primary icn-size-16">{{ $_icons['check'] }}</a></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		@endforeach
		</div>
	@else
		{{ partial('common/alert', array('content' => "No unpaid services found.")) }}
	@endif
@stop

@section('modals')
	{{ modal(array('id' => 'emailUser', 'header' => "Email User")) }}
@stop

@section('scripts')
	{{ View::make('partials.jsMarkAsPaid') }}
	<script>

		$('.js-email').on('click', function(e)
		{
			e.preventDefault();

			var id = $(this).data('user');

			$('#emailUser').modal({
				remote: "{{ URL::to('ajax/user/email/unpaid') }}/" + id
			}).modal('show');
		});

	</script>
@stop