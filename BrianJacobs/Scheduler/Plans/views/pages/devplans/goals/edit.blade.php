@extends('layouts.master')

@section('title')
	Edit Goal
@stop

@section('content')
	<h1>Edit Goal <small>{{ $goal->present()->title }}, {{ $plan->user->present()->name }}</small></h1>

	<div class="visible-md visible-lg">
		<div class="btn-toolbar">
			<div class="btn-group">
				<a href="{{ route('plan', $plan->user_id) }}" class="btn btn-sm btn-default icn-size-16">{{ $_icons['back'] }}</a>
			</div>
		</div>
	</div>
	<div class="visible-xs visible-sm">
		<div class="row">
			<div class="col-sm-3">
				<p><a href="{{ route('plan', $plan->user_id) }}" class="btn btn-lg btn-block btn-default">Back to Plan</a></p>
			</div>
		</div>
	</div>

	{{ Form::model($goal, ['route' => ['goal.update', $goal->id], 'method' => 'put', 'class' => 'form-horizontal']) }}
		<div class="form-group">
			<label class="control-label col-md-3">Name</label>
			<div class="col-md-5">
				{{ Form::text('title', null, ['class' => 'form-control input-lg']) }}
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-3">Summary</label>
			<div class="col-md-7">
				{{ Form::textarea('summary', null, ['class' => 'form-control input-lg', 'rows' => 5]) }}
			</div>
		</div>

		<div class="form-group">
			<div class="col-md-7 col-md-offset-3">
				<div class="checkbox">
					<label>{{ Form::checkbox('completion_option', true, ($goal->completion)) }} Allow goal auto-completion?</label>
				</div>
			</div>
		</div>

		<div id="completionControls" class="hide">
			<h2>Auto-Completion Criteria</h2>

			<div class="form-group">
				<label class="control-label col-md-3">Complete the Goal When</label>
				<div class="col-md-2">
					<p>{{ Form::text('completion[count]', 1, ['class' => 'form-control input-lg']) }}</p>
				</div>
				<div class="col-md-4">
					<p>{{ Form::select('completion[type]', $types, null, ['class' => 'form-control input-lg']) }}</p>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-3">Have a</label>
				<div class="col-md-3">
					<p>{{ Form::select('completion[metric]', [], null, ['class' => 'form-control input-lg']) }}</p>
				</div>
				<div class="col-md-3">
					<p>{{ Form::select('completion[operator]', $operators, null, ['class' => 'form-control input-lg']) }}</p>
				</div>
				<div class="col-md-3">
					<p>{{ Form::text('completion[value]', null, ['class' => 'form-control input-lg']) }}</p>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="col-md-9 col-md-offset-3">
				<div class="visible-xs visible-sm">
					{{ Form::button("Update Goal", ['type' => 'submit', 'class' => 'btn btn-primary btn-lg btn-block']) }}
				</div>
				<div class="visible-md visible-lg">
					{{ Form::button("Update Goal", ['type' => 'submit', 'class' => 'btn btn-primary btn-lg']) }}
				</div>
			</div>
		</div>
	{{ Form::close() }}
@stop

@section('scripts')
	<script>
		$(function()
		{
			$('[name="completion[type]"]').trigger('change');

			if ($('[name="completion_option"]:checked'))
				$('#completionControls').removeClass('hide');
		});

		$('[name="completion_option"]').on('change', function(e)
		{
			var checked = $(this).is(':checked');

			if (checked)
				$('#completionControls').removeClass('hide');
			else
			{
				$('#completionControls').addClass('hide');

				$('[name="completion[count]"]').val("0");
				$('[name="completion[type]"]').val("");
				$('[name="completion[metric]"]').val("");
				$('[name="completion[operator]"]').val("");
				$('[name="completion[value]"]').val("");
			}
		});

		var metricOptions = {
			"round": {
				"score": "Score",
				"fir": "FIRs",
				"gir": "GIRs",
				"putts": "Putts",
				"penalties": "Penalties",
				"holes": "Holes"
			},
			"practice": {
				"minutes": "Minutes",
				"balls": "Balls"
			},
			"tournament": {
				"score": "Final Score",
				"place": "Finishing Place"
			},
			"trackman": {
				"score": "Combine Score"
			}
		};

		$('[name="completion[type]"]').on('change', function(e)
		{
			// Clear the values
			$('[name="completion[metric]"]').find('option').remove();

			var selected = $('[name="completion[type]"] option:selected').val();
			var items;

			if (selected == "round")
				items = metricOptions.round;

			if (selected == "practice")
				items = metricOptions.practice;

			if (selected == "tournament")
				items = metricOptions.tournament;

			if (selected == "trackman")
				items = metricOptions.trackman;

			$.each(items, function(value, text)
			{
				$('[name="completion[metric]"]').append($('<option></option>').val(value).html(text));
			});

			@if ($goal->completion)
				$('[name="completion[metric]"] option[value="{{ $goal->completion->metric }}"]')
					.prop('selected', 'selected');
			@endif

			e.preventDefault();
		});
	</script>
@stop