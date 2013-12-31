@extends('layouts.master')

@section('title')
	Enroll in Program
@endsection

@section('content')
	<h1>Enroll in Program</h1>

	{{ Form::open(array('url' => 'book/program')) }}
		<div class="row">
			<div class="col-lg-4">
				<div class="form-group">
					<label class="control-label">Service</label>
					<div class="controls">
						{{ Form::select('service_id', $services, null, array('class' => 'form-control')) }}
						<p class="help-block" id="serviceDescription"></p>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-4">
				<div class="form-group">
					<label class="control-label">Date</label>
					<div class="controls">
						<span class="displayDate"></span>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-2">
				<div class="form-group">
					<label class="control-label">Start Time</label>
					<div class="controls">
						<span class="displayTime"></span>
					</div>
				</div>
			</div>
		</div>

		@if ($_currentUser->isStaff())
			<div class="row">
				<div class="col-lg-3">
					<div class="form-group">
						<label class="control-label">User</label>
						<div class="controls">
							{{ Form::select('user', User::all()->toSimpleArray('id', 'name'), $_currentUser->id, array('class' => 'form-control')) }}
						</div>
					</div>
				</div>
			</div>
		@else
			{{ Form::hidden('user', $_currentUser->id) }}
		@endif

		<div class="row">
			<div class="col-lg-4">
				<div class="form-group">
					<label class="control-label">Do you have a gift certificate?</label>
					<div class="controls">
						<label class="radio-inline">{{ Form::radio('gift_certificate', 1) }} Yes</label>
						<label class="radio-inline">{{ Form::radio('gift_certificate', 0) }} No</label>
					</div>
				</div>
			</div>
		</div>

		<div id="giftCertificateAmount" class="hide">
			<div class="row">
				<div class="col-lg-2">
					<div class="form-group">
						<label class="control-label">Amount</label>
						<div class="controls">
							<div class="input-group">
									<span class="input-group-addon"><strong>$</strong></span>
								{{ Form::text('gift_certificate_amount', null, array('class' => 'form-control')) }}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<div class="visible-lg">
					<p>{{ Form::submit('Enroll Now', array('class' => 'btn btn-primary')) }}</p>
				</div>
				<div class="hidden-lg">
					<p>{{ Form::submit('Enroll Now', array('class' => 'btn btn-block btn-lg btn-primary')) }}</p>
				</div>
			</div>
		</div>

		{{ Form::hidden('appointment_id', null) }}
	{{ Form::close() }}
@endsection

@section('scripts')
	<script>
		
		$(document).on('change', '[name="service_id"]', function(e)
		{
			var selected = $('[name="service_id"] option:selected').val();

			$.ajax({
				url: "{{ URL::route('ajax.getService') }}",
				data: { service: selected },
				success: function(data)
				{
					var obj = $.parseJSON(data);
					
					$('#serviceDescription').html(obj.service.description);
					$('.displayDate').html(obj.appointment.date);
					$('.displayTime').html(obj.appointment.start_time);
					$('[name="appointment_id"]').val(obj.appointment.id);
				}
			});
		});

		$(document).on('change', '[name="gift_certificate"]', function(e)
		{
			var selected = $('[name="gift_certificate"]:checked').val();

			if (selected == "1")
				$('#giftCertificateAmount').removeClass('hide');
			else
				$('#giftCertificateAmount').addClass('hide');
		});

	</script>
@endsection