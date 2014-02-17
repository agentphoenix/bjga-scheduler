@extends('layouts.master')

@section('title')
	Edit Appointment
@stop

@section('content')
	<h1>Edit Appointment</h1>

	<div class="visible-md visible-lg">
		<div class="btn-toolbar">
			@if ($_currentUser->access() > 1)
				<div class="btn-group">
					<a href="{{ URL::route('admin.user.index') }}" class="btn btn-sm btn-default icn-size-16">{{ $_icons['back'] }}</a>
				</div>
			@endif
		</div>
	</div>
	<div class="visible-xs visible-sm">
		<div class="row">
			@if ($_currentUser->access() > 1)
				<div class="col-xs-12 col-sm-3">
					<p><a href="{{ URL::route('admin.user.index') }}" class="btn btn-block btn-lg btn-default icn-size-16">{{ $_icons['back'] }}</a></p>
				</div>
			@endif
		</div>
	</div>

	{{ Form::model($appointment, array('route' => array('admin.appointment.update', $appointment->id), 'method' => 'put')) }}
		<div class="row">
			<div class="col-md-10 col-lg-8">
				<div class="form-group">
					<label class="control-label">Notes</label>
					{{ Form::textarea('notes', null, array('class' => 'form-control', 'rows' => 5)) }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6 col-md-5 col-lg-4">
				<div class="form-group{{ ($errors->has('start')) ? ' has-error' : '' }}">
					<label class="control-label">Start</label>
					{{ Form::text('start', null, array('class' => 'form-control input-with-feedback')) }}
					{{ $errors->first('start', '<p class="help-block">:message</p>') }}
				</div>
			</div>
			<div class="col-sm-6 col-md-5 col-lg-4">
				<div class="form-group{{ ($errors->has('end')) ? ' has-error' : '' }}">
					<label class="control-label">End</label>
					{{ Form::text('end', null, array('class' => 'form-control input-with-feedback')) }}
					{{ $errors->first('end', '<p class="help-block">:message</p>') }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6 col-md-5 col-lg-4">
				<div class="form-group">
					<label class="control-label">Has gift certificate?</label>
					<div class="controls">
						<label class="radio-inline text-sm">{{ Form::radio('has_gift', 1, ($appointment->userAppointments->first()->has_gift === 1)) }} Yes</label>
						<label class="radio-inline text-sm">{{ Form::radio('has_gift', 0, ($appointment->userAppointments->first()->has_gift === 0)) }} No</label>
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-md-3 col-lg-2">
				<div class="form-group">
					<label class="control-label">Amount</label>
					<div class="input-group">
						<span class="input-group-addon"><strong>$</strong></span>
						{{ Form::text('gift_amount', $appointment->userAppointments->first()->gift_amount, array('class' => 'form-control')) }}
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6 col-md-3 col-lg-2">
				<div class="form-group">
					<label class="control-label">Total Due</label>
					<div class="input-group">
						<span class="input-group-addon"><strong>$</strong></span>
						{{ Form::text('amount', $appointment->userAppointments->first()->amount, array('class' => 'form-control')) }}
					</div>
				</div>
			</div>
			<div class="visible-lg visible-md col-md-2 col-lg-2">&nbsp;</div>
			<div class="col-sm-6 col-md-5 col-lg-4">
				<div class="form-group">
					<label class="control-label">Paid?</label>
					<div class="controls">
						<label class="radio-inline text-sm">{{ Form::radio('paid', 1, ($appointment->userAppointments->first()->paid === 1)) }} Yes</label>
						<label class="radio-inline text-sm">{{ Form::radio('paid', 0, ($appointment->userAppointments->first()->paid === 0)) }} No</label>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<div class="visible-md visible-lg">
					{{ Form::button('Submit', array('type' => 'submit', 'class' => 'btn btn-lg btn-primary')) }}
				</div>
				<div class="visible-xs visible-sm">
					{{ Form::button('Submit', array('type' => 'submit', 'class' => 'btn btn-lg btn-block btn-primary')) }}
				</div>
			</div>
		</div>
	{{ Form::close() }}
@stop

@section('modals')
	{{ modal(array('id' => 'changePassword', 'header' => "Change Password")) }}
@stop

@section('scripts')
	<script type="text/javascript">
		
		$('.js-user-action').on('click', function(e)
		{
			e.preventDefault();

			var action = $(this).data('action');
			var id = $(this).data('id');

			if (action == 'password')
			{
				$('#changePassword').modal({
					remote: "{{ URL::to('ajax/user/password') }}/" + id
				}).modal('show');
			}
		});

	</script>
@stop