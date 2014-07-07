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

		<div class="row hide">
			<div class="col-lg-12">
				<div class="alert alert-warning">
					<p>You have 3 minutes to select a time for your lesson. If you don't select a time within 3 minutes, you will have to check availability again.</p>
					<p class="text-lg" id="availabilityCountdown"></p>
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

		<div class="row hide">
			<div class="col-lg-12">
				<div class="alert alert-warning">
					<p>Your selections have been made. You have 3 minutes to book your lesson for the selected date and time. If you don't book within 3 minutes, you will have to check availability again.</p>
					<p class="text-lg" id="finalCountdown"></p>
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
								{{ Form::select('user', UserModel::all()->lists('name', 'id'), $_currentUser->id, array('class' => 'form-control')) }}
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-lg-6">
						<div class="form-group">
							<label class="control-label">Notes</label>
							<div class="controls">
								{{ Form::textarea('notes', false, array('class' => 'form-control', 'rows' => 5)) }}
							</div>
						</div>
					</div>
				</div>
			@else
				{{ Form::hidden('user', $_currentUser->id) }}
				{{ Form::hidden('notes', '') }}
			@endif

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

@section('styles')
	{{ HTML::style('css/picker.default.css') }}
	{{ HTML::style('css/picker.default.date.css') }}
@stop

@section('scripts')
	{{ HTML::script('js/moment.min.js') }}
	{{ HTML::script('js/jquery.plugin.min.js') }}
	{{ HTML::script('js/jquery.countdown.min.js') }}
	{{ HTML::script('js/picker.js') }}
	{{ HTML::script('js/picker.date.js') }}
	{{ HTML::script('js/picker.legacy.js') }}
	<script>

		var timer;

		$(document).on('click', '.js-check', function(e)
		{
			e.preventDefault();

			if ($('[name="service_id"] option:selected').val() == "0")
			{
				alert("Please select a service");
			}
			else if ($('[name="date"]').val() == "")
			{
				alert("Please select a date");
			}
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

						var hasAvailability = $('.ajax-container:has(p.alert)').length;

						if (hasAvailability == 0)
						{
							// Start the countdown
							/*$('#availabilityCountdown').countdown({
								until: moment().add('minutes', 3).toDate(),
								compact: true, 
								layout: "{mnn}{sep}{snn}"
							});

							// Show the timer
							$('#availabilityCountdown').closest('.row').removeClass('hide');*/

							// 5 minutes... GO!
							timer = setTimeout("resetOptions()", 300000);
						}
					}
				});
			}
		});

		$(document).on('click', '.js-book', function(e)
		{
			e.preventDefault();

			$('[name="time"]').val($(this).data('time'));
			$('[name="timeDisplay"]').val(moment($(this).data('time'), "HH:mm").format("h:mm A"));
			$('.js-check').closest('.row').addClass('hide');
			$('.ajax-container').closest('.row').addClass('hide');
			$('.bookingForm').removeClass('hide');
			//$('#availabilityCountdown').closest('.row').addClass('hide');

			// Start the countdown
			/*$('#finalCountdown').countdown({
				until: moment().add('minutes', 3).toDate(),
				compact: true, 
				layout: "{mnn}{sep}{snn}"
			});

			// Show the timer
			$('#finalCountdown').closest('.row').removeClass('hide');*/

			// 5 minutes... GO!
			timer = setTimeout("resetOptions()", 300000);
		});

		$('[name="service_id"]').on('change', function(e)
		{
			resetOptions();

			$.ajax({
				url: "{{ URL::route('ajax.getLessonService') }}",
				data: { service: $('[name="service_id"] option:selected').val() },
				success: function(data)
				{
					$('#lessonServiceDetails').html(data);
				}
			});
		});

		$('.js-change-time').on('click', function(e)
		{
			resetOptions();
		});

		$(function()
		{
			$('.js-datepicker').pickadate({
				format: "yyyy-mm-dd",
				min: 1,
				max: false,
				container: '.container-fluid',
				today: false,
				editable: true,
				onOpen: function()
				{
					$('[name="time"]').val('');
					$('[name="timeDisplay"]').val('');
					$('.bookingForm').addClass('hide');
					$('.ajax-container').html('').closest('.row').removeClass('hide');
					$('.js-check').closest('.row').removeClass('hide');
				}
			});
		});

		function resetOptions()
		{
			clearTimeout(timer);

			$('#lessonServiceDetails').html('');
			$('.ajax-container').html('').closest('.row').removeClass('hide');
			//$('#availabilityCountdown').closest('.row').addClass('hide');
			$('[name="time"]').val('');
			$('[name="timeDisplay"]').val('');
			$('.bookingForm').addClass('hide');
			$('.js-check').closest('.row').removeClass('hide');

			/*if ($.isFunction($.countdown))
			{
				$('#availabilityCountdown').countdown('destroy');
				$('#finalCountdown').countdown('destroy');
			}

			$('#availabilityCountdown').closest('.row').addClass('hide');
			$('#finalCountdown').closest('.row').addClass('hide');*/
		}

	</script>
@stop