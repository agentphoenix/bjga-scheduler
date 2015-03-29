{{ Form::open(['route' => ['admin.staff.schedule.update', $staff->id], 'method' => 'put']) }}
	<div class="row">
		<div class="col-lg-6">
			<div class="form-group">
				<label class="control-label">Day</label>
				<p>{{ $day }}</p>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">
				<div class="checkbox text-sm">
					<label>
						{{ Form::checkbox('no_times', 1, false) }} No availability on {{ $day }}s
					</label>
				</div>
			</div>
		</div>
	</div>

	<div id="scheduleTimes">
		<div class="row">
			<div class="col-lg-6">
				<div class="form-group">
					<label class="control-label">Start Time</label>
					{{ Form::text('start', $start, ['class' => 'form-control js-timepicker-start']) }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-6">
				<div class="form-group">
					<label class="control-label">End Time</label>
					{{ Form::text('end', $end, ['class' => 'form-control js-timepicker-end']) }}
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-6">
			<div class="form-group">
				<label class="control-label">Location</label>
				{{ Form::select('location', $locations, null, ['class' => 'form-control']) }}
			</div>
		</div>
	</div>

	{{ Form::hidden('oldLocation', $staff->getScheduleForDay($daynum)->location_id) }}
	{{ Form::hidden('dayNum', $daynum) }}

	<div class="visible-md visible-lg">
		{{ Form::button("Update Schedule", ['type' => 'submit', 'class' => 'btn btn-lg btn-primary']) }}
	</div>
	<div class="visible-xs visible-sm">
		{{ Form::button("Update Schedule", ['type' => 'submit', 'class' => 'btn btn-lg btn-block btn-primary']) }}
	</div>
{{ Form::close() }}

{{ HTML::style('css/picker.default.css') }}
{{ HTML::style('css/picker.default.time.css') }}
{{ HTML::script('js/picker.js') }}
{{ HTML::script('js/picker.time.js') }}
<script>
	$('[name="no_times"]').on('change', function(e)
	{
		if ($('[name="no_times"]').is(':checked'))
		{
			$('#scheduleTimes').addClass('hide');
			$('[name="start"]').val("");
			$('[name="end"]').val("");
		}
		else
		{
			$('#scheduleTimes').removeClass('hide');
		}
	});

	$(function()
	{
		$('.js-timepicker-start').pickatime({
			format: "h:i a",
			formatSubmit: "HH:i",
			hiddenName: true,
			interval: 15,
			min: [6, 0],
			max: [22, 0],
			container: '.container-fluid'
		});

		$('.js-timepicker-end').pickatime({
			format: "h:i a",
			formatSubmit: "HH:i",
			hiddenName: true,
			interval: 15,
			min: [6, 0],
			max: [22, 0],
			container: '.container-fluid'
		});
	});
</script>