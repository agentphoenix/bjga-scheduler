@extends('layouts.master')

@section('title')
	Enroll in a Program
@stop

@section('content')
	<h1>Enroll in a Program</h1>

	{{ Form::open(array('route' => 'book.program.store')) }}
		<div class="row">
			<div class="col-lg-6">
				<div class="form-group">
					<label class="control-label">Service</label>
					<div class="controls">
						{{ Form::select('service_id', $services, null, array('class' => 'form-control')) }}
					</div>
				</div>
			</div>
		</div>

		<div id="serviceDescription"></div>

		<div id="programServiceDetails"></div>

		@if ($_currentUser->isStaff())
			<div class="row">
				<div class="col-lg-3">
					<div class="form-group">
						<label class="control-label">User</label>
						<div class="controls">
							{{ Form::select('user', UserModel::all()->sortBy('name')->lists('name', 'id'), $_currentUser->id, array('class' => 'form-control')) }}
						</div>
					</div>
				</div>
			</div>
		@else
			{{ Form::hidden('user', $_currentUser->id) }}
		@endif

		<!--<div class="row">
			<div class="col-lg-4">
				<div class="form-group">
					<label class="control-label">Do you have a gift certificate?</label>
					<div class="controls">
						<label class="radio-inline text-sm">{{ Form::radio('has_gift', 1) }} Yes</label>
						<label class="radio-inline text-sm">{{ Form::radio('has_gift', 0) }} No</label>
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
								{{ Form::text('gift_amount', null, array('class' => 'form-control')) }}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>-->

		<div class="row hide" id="enrollBtn">
			<div class="col-lg-12">
				<div class="visible-md visible-lg">
					<p>{{ Form::submit('Enroll Now', array('class' => 'btn btn-lg btn-primary')) }}</p>
				</div>
				<div class="visible-xs visible-sm">
					<p>{{ Form::submit('Enroll Now', array('class' => 'btn btn-block btn-lg btn-primary')) }}</p>
				</div>
			</div>
		</div>

		<p id="noEnroll" class="hide alert alert-danger">There are no more slots available for this program!</p>

		{{ Form::hidden('appointment_id', null) }}
	{{ Form::close() }}
@stop

@section('scripts')
	<script>
		
		$(document).on('change', '[name="service_id"]', function(e)
		{
			var selected = $('[name="service_id"] option:selected').val();
			
			if (selected != "")
				$('#enrollBtn').removeClass('hide');

			$.ajax({
				url: "{{ URL::route('ajax.getService') }}",
				data: { service: selected },
				success: function(data)
				{
					var obj = $.parseJSON(data);
					
					$('#serviceDescription').html(obj.service.description);
					$('[name="appointment_id"]').val(obj.appointment.id);

					if (obj.service.user_limit == obj.enrolled)
					{
						$('#enrollBtn').addClass('hide');
						$('#noEnroll').removeClass('hide');
					}
				}
			});

			$.ajax({
				url: "{{ URL::route('ajax.getProgramService') }}",
				data: { service: selected },
				success: function(data)
				{
					$('#programServiceDetails').html(data);
				}
			});
		});

		$(document).on('change', '[name="has_gift"]', function(e)
		{
			var selected = $('[name="has_gift"]:checked').val();

			if (selected == "1")
				$('#giftCertificateAmount').removeClass('hide');
			else
				$('#giftCertificateAmount').addClass('hide');
		});

	</script>
@stop