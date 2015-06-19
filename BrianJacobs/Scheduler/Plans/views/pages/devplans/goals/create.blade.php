{{ Form::open(['route' => 'goal.store', 'class' => 'form-horizontal']) }}
	<div class="form-group">
		<label class="control-label col-md-3">Name</label>
		<div class="col-md-9">
			{{ Form::text('title', null, ['class' => 'form-control input-lg']) }}
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-md-3">Summary</label>
		<div class="col-md-9">
			{{ Form::textarea('summary', null, ['class' => 'form-control input-lg', 'rows' => 5]) }}
		</div>
	</div>

	<!--<div class="form-group">
		<label class="control-label col-md-3">Target Date</label>
		<div class="col-md-9">
			{{ Form::text('target_date', null, ['class' => 'form-control input-lg js-datepicker']) }}
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

{{ HTML::style('css/picker.default.css') }}
{{ HTML::style('css/picker.default.date.css') }}

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
</script>