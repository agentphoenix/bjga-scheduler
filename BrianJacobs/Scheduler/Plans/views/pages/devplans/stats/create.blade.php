<p>You can add as little or as much of the information below about your stats for your round/practice session/TrackMan Combine for you and your instructor(s) to use working toward your goals.</p>

{{ Form::open(['route' => ['stats.store', $goal->id], 'class' => 'form-horizontal']) }}
	<div class="form-group">
		<label class="control-label col-md-3">Type</label>
		<div class="col-md-6">
			{{ Form::select('type', $types, null, ['class' => 'form-control input-lg']) }}
		</div>
	</div>

	<div id="roundStats" class="hide">
		<div class="form-group">
			<label class="control-label col-md-3">Course</label>
			<div class="col-md-9">
				{{ Form::text('course', null, ['class' => 'form-control input-lg']) }}
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-3">No. of Holes</label>
			<div class="col-md-6">
				{{ Form::select('numHoles', $holes, null, ['class' => 'form-control input-lg']) }}
			</div>
		</div>

		<div class="form-group hide" id="otherHoles">
			<div class="col-md-6 col-md-offset-3">
				{{ Form::text('holes', 9, ['class' => 'form-control input-lg', 'placeholder' => 'Number of holes']) }}
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-3">Score</label>
			<div class="col-md-3">
				{{ Form::text('score', null, ['class' => 'form-control input-lg']) }}
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-3">Fairways</label>
			<div class="col-md-3">
				{{ Form::text('fir', null, ['class' => 'form-control input-lg']) }}
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-3">Greens</label>
			<div class="col-md-3">
				{{ Form::text('gir', null, ['class' => 'form-control input-lg']) }}
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-3">Putts</label>
			<div class="col-md-3">
				{{ Form::text('putts', null, ['class' => 'form-control input-lg']) }}
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-3">Penalties</label>
			<div class="col-md-3">
				{{ Form::text('penalties', null, ['class' => 'form-control input-lg']) }}
			</div>
		</div>
	</div>

	<div id="practiceStats" class="hide">
		<div class="form-group">
			<label class="control-label col-md-3">Minutes</label>
			<div class="col-md-3">
				{{ Form::text('minutes', null, ['class' => 'form-control input-lg']) }}
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-3">No. of Balls</label>
			<div class="col-md-3">
				{{ Form::text('balls', null, ['class' => 'form-control input-lg']) }}
			</div>
		</div>
	</div>

	<div id="trackmanStats" class="hide">
		<div class="form-group">
			<label class="control-label col-md-3">Score</label>
			<div class="col-md-3">
				{{ Form::text('score', null, ['class' => 'form-control input-lg']) }}
			</div>
		</div>
	</div>

	<div id="tournamentStats" class="hide">
		<div class="form-group">
			<label class="control-label col-md-3">Tournament</label>
			<div class="col-md-9">
				{{ Form::text('tournament', null, ['class' => 'form-control input-lg']) }}
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-3">No. of Players</label>
			<div class="col-md-3">
				{{ Form::text('players', null, ['class' => 'form-control input-lg']) }}
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-3">Place</label>
			<div class="col-md-3">
				{{ Form::text('place', null, ['class' => 'form-control input-lg']) }}
			</div>
		</div>
	</div>

	<div id="controls" class="hide">
		<div class="form-group">
			<label class="control-label col-md-3">Notes</label>
			<div class="col-md-9">
				{{ Form::textarea('notes', null, ['class' => 'form-control input-lg', 'rows' => 5]) }}
			</div>
		</div>

		{{ Form::hidden('goal_id', $goal->id) }}

		<div class="form-group">
			<div class="col-md-9 col-md-offset-3">
				<div class="visible-xs visible-sm">
					{{ Form::button("Add Stats", ['type' => 'submit', 'class' => 'btn btn-primary btn-lg btn-block']) }}
				</div>
				<div class="visible-md visible-lg">
					{{ Form::button("Add Stats", ['type' => 'submit', 'class' => 'btn btn-primary btn-lg']) }}
				</div>
			</div>
		</div>
	</div>
{{ Form::close() }}

<script>
	$('[name="type"]').change(function()
	{
		var selected = $('[name="type"] option:selected').val();

		resetSection('#practiceStats');
		resetSection('#trackmanStats');
		resetSection('#roundStats');
		resetSection('#tournamentStats');

		$("#" + selected + "Stats").removeClass('hide');
		$('#controls').removeClass('hide');
	});

	$('[name="numHoles"]').change(function()
	{
		var selected = $('[name="numHoles"] option:selected').val();

		if (selected == "other")
		{
			$('#otherHoles').removeClass('hide');
			$('[name="holes"]').val("");
		}
		else
		{
			$('#otherHoles').addClass('hide');
			$('[name="holes"]').val(selected);
		}
	});

	function resetSection(section)
	{
		$(section + " input").each(function()
		{
			$(this).val("");
		});

		$(section).addClass('hide');
	}
</script>