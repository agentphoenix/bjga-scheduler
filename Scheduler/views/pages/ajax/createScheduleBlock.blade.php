<p>Select the date your schedule block will be on. You can choose to block your entire schedule for that day or just for specific times by unchecking the <em>Block full schedule</em> option.</p>

{{ Form::open(array('route' => array('admin.staff.block.store', $user->staff->id))) }}
	<div class="row">
		<div class="col-lg-4">
			<div class="form-group">
				<label class="control-label">Date</label>
				{{ Form::text('date', null, array('class' => 'form-control js-datepicker')) }}
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-4">
			<div class="form-group">
				<div class="checkbox">
					<label>
						{{ Form::checkbox('all_day', 1, true) }} Block full schedule
					</label>
				</div>
			</div>
		</div>
	</div>

	<div class="hide" id="blockTimes">
		<div class="row">
			<div class="col-lg-4">
				<div class="form-group">
					<label class="control-label">Start Time</label>
					{{ Form::text('start', null, array('class' => 'form-control js-timepicker-start')) }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-4">
				<div class="form-group">
					<label class="control-label">End Time</label>
					{{ Form::text('end', null, array('class' => 'form-control js-timepicker-end')) }}
				</div>
			</div>
		</div>
	</div>

	{{ Form::hidden('staff_id', $user->staff->id) }}

	<div class="visible-lg">
		{{ Form::submit("Create Block", array('class' => 'btn btn-lg btn-primary')) }}
	</div>
	<div class="hidden-lg">
		{{ Form::submit("Create Block", array('class' => 'btn btn-lg btn-block btn-primary')) }}
	</div>
{{ Form::close() }}

<script src="{{ URL::asset('js/moment.min.js') }}"></script>
<script src="{{ URL::asset('js/bootstrap-datetimepicker.min.js') }}"></script>
<script>
	$(document).on('change', '[name="all_day"]', function()
	{
		var checked = $(this).is(':checked');

		if (checked)
			$('#blockTimes').addClass('hide');
		else
			$('#blockTimes').removeClass('hide');
	});

	$(function()
	{
		$('.js-datepicker').datetimepicker({
			pickTime: false,
			format: "YYYY-MM-DD"
		});

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