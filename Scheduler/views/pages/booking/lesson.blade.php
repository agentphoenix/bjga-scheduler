@extends('layouts.master')

@section('title')
	Book Lesson
@stop

@section('content')
	<h1>Book Lesson</h1>

	{{ Form::open(array('route' => 'book.lesson.store')) }}
		<div class="row">
			<div class="col-sm-8 col-md-6 col-lg-4">
				<div class="form-group">
					<label class="control-label">Service</label>
					<div class="controls">
						{{ Form::select('service_id', $services, null, array('class' => 'form-control')) }}
						<div id="serviceDescription"></div>
					</div>
				</div>
			</div>
			<div class="col-sm-4 col-md-6 col-lg-8">
				<div id="lessonServiceDetails"></div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-4 col-md-3 col-lg-2">
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
					<div class="visible-md visible-lg">
						<a class="btn btn-lg btn-primary js-check">Check Availability</a>
					</div>
					<div class="visible-xs visible-sm">
						<p><a class="btn btn-lg btn-block btn-primary js-check">Check Availability</a></p>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<div class="form-group">
					<div class="ajax-container"></div>
				</div>
			</div>
		</div>

		<div class="hide bookingForm">
			<div class="row">
				<div class="col-lg-12">
					<div class="form-group">
						<label class="control-label">Start Time</label>
						<div class="row">
							<div class="col-xs-7 col-sm-4 col-md-3 col-lg-2">
								<div class="controls">
									{{ Form::text('timeDisplay', null, array('class' => 'form-control', 'disabled' => 'disabled')) }}
									{{ Form::hidden('time', null) }}
								</div>
							</div>
							<div class="col-xs-5 col-sm-8 col-md-9 col-lg-10">
								<button class="btn btn-sm btn-default js-change-time" type="button" style="padding-bottom:6px;">Change Time</button>
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
								{{ Form::select('user', UserModel::all()->toSimpleArray('id', 'name'), $_currentUser->id, array('class' => 'form-control')) }}
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

			<div class="hide giftCertificateAmount">
				<div class="row">
					<div class="col-sm-3 col-md-3 col-lg-2">
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

			<div class="row">
				<div class="col-lg-12">
					<div class="visible-md visible-lg">
						<p>
							{{ Form::submit('Book Now', array('class' => 'btn btn-lg btn-primary')) }}
							<a href="{{ URL::route('home') }}" class="btn btn-link">Cancel</a>
						</p>
					</div>
					<div class="visible-xs visible-sm">
						<p>{{ Form::submit('Book Now', array('class' => 'btn btn-block btn-lg btn-primary')) }}</p>
						<p><a href="{{ URL::route('home') }}" class="btn btn-lg btn-block btn-default">Cancel</a></p>
					</div>
				</div>
			</div>
		</div>
	{{ Form::close() }}
@stop

@section('scripts')
	<script src="{{ URL::asset('js/moment.min.js') }}"></script>
	<script src="{{ URL::asset('js/bootstrap-datetimepicker.min.js') }}"></script>
	<script>

		$(document).on('click', '.js-check', function(e)
		{
			e.preventDefault();

			if ($('[name="service_id"] option:selected').val() == "0")
				alert("Please select a service");
			else
			{
				$.ajax({
					data: {
						'service': $('[name="service_id"] option:selected').val(),
						'date': $('[name="date"]').val()
					},
					url: "{{ URL::to('ajax/availability') }}",
					success: function(data)
					{
						$('.ajax-container').html(data);
					}
				});
			}
		});

		$(document).on('click', '.js-book', function(e)
		{
			e.preventDefault();

			$('[name="time"]').val($(this).data('time'));
			$('[name="timeDisplay"]').val(moment($(this).data('time'), "HH:mm A").format("h:mm A"));
			$('.js-check').closest('.row').addClass('hide');
			$('.ajax-container').closest('.row').addClass('hide');
			$('.bookingForm').removeClass('hide');
		});

		$('[name="service_id"]').on('change', function(e)
		{
			$.ajax({
				url: "{{ URL::route('ajax.getLessonService') }}",
				data: { service: $('[name="service_id"] option:selected').val() },
				success: function(data)
				{
					$('#lessonServiceDetails').html(data);
				}
			});
		});

		$('[name="has_gift"]').on('change', function(e)
		{
			if ($('[name="has_gift"]:checked').val() == "1")
				$('.giftCertificateAmount').removeClass('hide');
			else
				$('.giftCertificateAmount').addClass('hide');
		});

		$('.js-change-time').on('click', function(e)
		{
			$('.bookingForm').addClass('hide');
			$('.ajax-container').html('').closest('.row').removeClass('hide');
			$('.js-check').closest('.row').removeClass('hide');
		});

		$(document).on('show.dp', function()
		{
			$('[name="time"]').val('');
			$('[name="timeDisplay"]').val('');
			$('.bookingForm').addClass('hide');
			$('.ajax-container').html('').closest('.row').removeClass('hide');
			$('.js-check').closest('.row').removeClass('hide');
		});

		$(function()
		{
			var now = moment();

			$('.js-datepicker').datetimepicker({
				pickTime: false,
				format: "YYYY-MM-DD",
				defaultDate: now,
				startDate: now
			});
		});

	</script>
@stop