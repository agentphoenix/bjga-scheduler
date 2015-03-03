<p>Select the date your schedule block will be on. You can choose to block your entire schedule for that day or just for specific times by unchecking the <em>Block full schedule</em> option.</p>

{{ Form::open(['route' => ['admin.staff.block.store', $user->staff->id], 'id' => 'createBlock']) }}
	<div class="row">
		<div class="col-sm-4 col-md-4 col-lg-4">
			<div class="form-group">
				<label class="control-label">Date</label>
				{{ Form::text('date', null, ['class' => 'form-control js-datepicker']) }}
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-4 col-md-4 col-lg-4">
			<div class="form-group">
				<div class="checkbox text-sm">
					<label>
						{{ Form::checkbox('all_day', 1, true) }} Block full schedule
					</label>
				</div>
			</div>
		</div>
	</div>

	<div class="hide" id="blockTimes">
		<div class="row">
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<div class="form-group">
					<label class="control-label">Start Time</label>
					{{ Form::text('start', null, ['class' => 'form-control js-timepicker-start']) }}
				</div>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<div class="form-group">
					<label class="control-label">End Time</label>
					{{ Form::text('end', null, ['class' => 'form-control js-timepicker-end']) }}
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<div class="form-group">
				<label class="control-label">Notes</label>
				<div class="controls">
					{{ Form::textarea('notes', false, ['class' => 'form-control', 'rows' => 5]) }}
				</div>
			</div>
		</div>
	</div>

	{{ Form::hidden('staff_id', $user->staff->id) }}

	<div class="visible-md visible-lg">
		{{ Form::button("Block Schedule", ['type' => 'submit', 'class' => 'btn btn-lg btn-primary']) }}
	</div>
	<div class="visible-xs visible-sm">
		{{ Form::button("Block Schedule", ['type' => 'submit', 'class' => 'btn btn-lg btn-block btn-primary']) }}
	</div>
{{ Form::close() }}

{{ HTML::style('css/picker.default.css') }}
{{ HTML::style('css/picker.default.date.css') }}
{{ HTML::style('css/picker.default.time.css') }}
{{ HTML::script('js/picker.js') }}
{{ HTML::script('js/picker.date.js') }}
{{ HTML::script('js/picker.time.js') }}
<script>
	$(document).on('change', '[name="all_day"]', function()
	{
		var checked = $(this).is(':checked');

		if (checked)
			$('#blockTimes').addClass('hide');
		else
			$('#blockTimes').removeClass('hide');
	});

	$('#createBlock').on('submit', function(e)
	{
		var allDay = $('[name="all_day"]').is(':checked');
		var date = $('[name="date"]').val();
		var start = $('[name="start"]').val();
		var end = $('[name="end"]').val();

		if (date == "")
		{
			alert("Please enter a date to continue creating your schedule block.");
			return false;
		}

		if ( ! allDay && (start == "" || end == ""))
		{
			alert("Please enter both a start time and end time to continue creating your schedule block.");
			return false;
		}
	});

	$(function()
	{
		$('.js-datepicker').pickadate({
			format: "yyyy-mm-dd",
			max: false,
			container: '.container-fluid'
		});

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