{{ Form::open(array('route' => array('admin.staff.schedule.update', $staff->id), 'method' => 'put')) }}
	<div class="row">
		<div class="col-lg-6">
			<div class="form-group">
				<label class="control-label">Day</label>
				<p>{{ $day }}</p>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-6">
			<div class="form-group">
				<label class="control-label">Start Time</label>
				{{ Form::text('start', null, array('class' => 'form-control js-timepicker-start')) }}
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-6">
			<div class="form-group">
				<label class="control-label">End Time</label>
				{{ Form::text('end', null, array('class' => 'form-control js-timepicker-end')) }}
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
		{{ Form::submit("Update Schedule", array('class' => 'btn btn-lg btn-primary')) }}
	</div>
	<div class="visible-xs visible-sm">
		{{ Form::submit("Update Schedule", array('class' => 'btn btn-lg btn-block btn-primary')) }}
	</div>
{{ Form::close() }}

{{ HTML::style('css/picker.default.css') }}
{{ HTML::style('css/picker.default.time.css') }}
{{ HTML::script('js/picker.js') }}
{{ HTML::script('js/picker.time.js') }}
{{ HTML::script('js/picker.legacy.js') }}
<script>
	$(function()
	{
		$('.js-timepicker-start').pickatime({
			format: "HH:i A",
			interval: 15,
			min: [6, 0],
			max: [22, 0],
			container: '.container-fluid'
		});

		$('.js-timepicker-end').pickatime({
			format: "HH:i A",
			interval: 15,
			min: [6, 0],
			max: [22, 0],
			container: '.container-fluid'
		});
	});
</script>