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

	{{ Form::hidden('dayNum', $daynum) }}

	<div class="visible-md visible-lg">
		{{ Form::submit("Update Schedule", array('class' => 'btn btn-lg btn-primary')) }}
	</div>
	<div class="visible-xs visible-sm">
		{{ Form::submit("Update Schedule", array('class' => 'btn btn-lg btn-block btn-primary')) }}
	</div>
{{ Form::close() }}

<script src="{{ URL::asset('js/moment.min.js') }}"></script>
<script src="{{ URL::asset('js/bootstrap-datetimepicker.min.js') }}"></script>
<script>
	$(function()
	{
		$('.js-timepicker-start').datetimepicker({
			pickDate: false,
			format: "HH:mm A"
		});

		$('.js-timepicker-end').datetimepicker({
			pickDate: false,
			format: "HH:mm A"
		});
	});
</script>