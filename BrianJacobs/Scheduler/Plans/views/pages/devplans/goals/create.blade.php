{{ Form::open(['route' => 'goal.store', 'class' => 'form-horizontal']) }}
	<div class="form-group">
		<label class="control-label col-md-2">Name</label>
		<div class="col-md-10">
			{{ Form::text('title', null, ['class' => 'form-control input-lg']) }}
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-md-2">Summary</label>
		<div class="col-md-10">
			{{ Form::textarea('summary', null, ['class' => 'form-control input-lg', 'rows' => 5]) }}
		</div>
	</div>

	{{ Form::hidden('plan_id', $plan->id) }}

	<div class="form-group">
		<div class="col-md-8 col-md-offset-2">
			<div class="visible-xs visible-sm">
				{{ Form::button("Add Goal", ['type' => 'submit', 'class' => 'btn btn-primary btn-lg btn-block']) }}
			</div>
			<div class="visible-md visible-lg">
				{{ Form::button("Add Goal", ['type' => 'submit', 'class' => 'btn btn-primary btn-lg']) }}
			</div>
		</div>
	</div>
{{ Form::close() }}