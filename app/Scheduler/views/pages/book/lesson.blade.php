@extends('layouts.master')

@section('title')
	Book Private Instruction
@endsection

@section('content')
	<h1>Book Private Instruction</h1>

	{{ Form::open(array('url' => 'book/lesson')) }}
		<div class="row">
			<div class="col-lg-4">
				<div class="form-group">
					<label class="control-label">Service</label>
					<div class="controls">
						{{ Form::select('service_id', $services, null, array('class' => 'form-control')) }}
						<div id="serviceDescription"></div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-2">
				<div class="form-group">
					<label class="control-label">Date</label>
					<div class="controls">
						{{ Form::text('date', null, array('class' => 'form-control js-datepicker')) }}
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-4">
				<div class="form-group">
					<div class="visible-lg">
						<a href="{{ URL::to('ajax/availability') }}" class="btn btn-primary" id="checkAvailability">Check Availability</a>
					</div>
					<div class="hidden-lg">
						<p><a href="{{ URL::to('ajax/availability') }}" class="btn btn-block btn-primary" id="checkAvailability">Check Availability</a></p>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<div class="form-group">
					<div id="ajax-container"></div>
				</div>
			</div>
		</div>

		<div class="hide" id="bookingForm">
			<div class="row">
				<div class="col-lg-2">
					<div class="form-group">
						<label class="control-label">Start Time</label>
						<div class="controls">
							<div class="input-group">
								{{ Form::text('timeDisplay', null, array('class' => 'form-control', 'disabled' => 'disabled')) }}
								{{ Form::hidden('time', null) }}
								<span class="input-group-btn">
									<button class="btn btn-sm btn-default js-change-time" type="button" style="padding-bottom:6px;">Change Time</button>
								</span>
							</div>
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
						<p>{{ Form::submit('Book Now', array('class' => 'btn btn-primary')) }}</p>
					</div>
					<div class="hidden-lg">
						<p>{{ Form::submit('Book Now', array('class' => 'btn btn-block btn-lg btn-primary')) }}</p>
					</div>
				</div>
			</div>
		</div>
	{{ Form::close() }}
@endsection

@section('scripts')
	<script src="{{ URL::asset('js/moment.min.js') }}"></script>
	<script src="{{ URL::asset('js/bootstrap-datetimepicker.js') }}"></script>
	<script>
		
		$(document).on('click', '#checkAvailability', function(e)
		{
			e.preventDefault();

			$.ajax({
				data: {
					'service': $('[name="service_id"] option:selected').val(),
					'date': $('[name="date"]').val()
				},
				url: this.href,
				success: function(data)
				{
					$('#ajax-container').html(data);
				}
			});
		});

		$(document).on('click', '.js-book', function(e)
		{
			e.preventDefault();

			$('[name="time"]').val($(this).data('time'));
			$('[name="timeDisplay"]').val($(this).data('time'));
			$('#checkAvailability').closest('.row').addClass('hide');
			$('#ajax-container').closest('.row').addClass('hide');
			$('#bookingForm').removeClass('hide');
		});

		$(document).on('change', '[name="gift_certificate"]', function(e)
		{
			var selected = $('[name="gift_certificate"]:checked').val();

			if (selected == "1")
				$('#giftCertificateAmount').removeClass('hide');
			else
				$('#giftCertificateAmount').addClass('hide');
		});

		$(document).on('click', '.js-change-time', function(e)
		{
			$('#bookingForm').addClass('hide');
			$('#ajax-container').html('').closest('.row').removeClass('hide');
			$('#checkAvailability').closest('.row').removeClass('hide');
		});

		$(function()
		{
			$('.js-datepicker').datetimepicker({
				pickTime: false,
				format: "YYYY-MM-DD",
				defaultDate: moment(),
				startDate: moment()
			});
		});

	</script>
@endsection