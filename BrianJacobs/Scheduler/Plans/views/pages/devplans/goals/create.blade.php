@extends('layouts.master')

@section('title')
	Add a Goal
@stop

@section('content')
	<h1>Add a Goal</h1>

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

	{{ Form::open(['route' => 'goal.store', 'class' => 'form-horizontal']) }}
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

		<!--<div class="form-group">
			<div class="col-md-7 col-md-offset-3">
				<div class="checkbox">
					<label>{{ Form::checkbox('completion', true, false) }} Allow goal auto-completion?</label>
				</div>
			</div>
		</div>

		<div id="completionControls" class="">
			<h2>Auto-Completion Criteria</h2>

			<div class="form-group">
				<label class="control-label col-md-3">Complete the Goal When</label>
				<div class="col-md-2">
					<p>{{ Form::text('count', 1, ['class' => 'form-control input-lg']) }}</p>
				</div>
				<div class="col-md-4">
					<p>{{ Form::select('type', $types, null, ['class' => 'form-control input-lg']) }}</p>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-3">Have a</label>
				<div class="col-md-3">
					<p>{{ Form::select('metric', $metrics, null, ['class' => 'form-control input-lg']) }}</p>
				</div>
				<div class="col-md-3">
					<p>{{ Form::select('operator', $operators, null, ['class' => 'form-control input-lg']) }}</p>
				</div>
				<div class="col-md-3">
					<p>{{ Form::text('value', null, ['class' => 'form-control input-lg']) }}</p>
				</div>
			</div>
		</div>-->

		{{ Form::hidden('plan_id', $plan->id) }}

		<div class="form-group">
			<div class="col-md-9 col-md-offset-3">
				<div class="visible-xs visible-sm">
					{{ Form::button("Add Goal", ['type' => 'submit', 'class' => 'btn btn-primary btn-lg btn-block']) }}
				</div>
				<div class="visible-md visible-lg">
					{{ Form::button("Add Goal", ['type' => 'submit', 'class' => 'btn btn-primary btn-lg']) }}
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
	{{ HTML::script('js/picker.js') }}
	{{ HTML::script('js/picker.date.js') }}
	<script>
		$(function()
		{
			$('.js-datepicker').pickadate({
				format: "dddd, mmmm dd, yyyy",
				formatSubmit: "yyyy-mm-dd",
				min: 1,
				max: false,
				container: '.container-fluid',
				today: false
			});
		});

		$('[name="completion"]').on('change', function(e)
		{
			var checked = $(this).is(':checked');

			if (checked)
				$('#completionControls').removeClass('hide');
			else
			{
				$('#completionControls').addClass('hide');

				$('[name="count"]').val("0");
				$('[name="type"]').val("");
				$('[name="metric"]').val("");
				$('[name="operator"]').val("");
				$('[name="value"]').val("");
			}
		});
	</script>
@stop