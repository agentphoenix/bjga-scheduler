{{ Form::open(['route' => 'admin.appointment.associateGoal', 'method' => 'put', 'class' => 'form-horizontal']) }}
	<div class="form-group">
		<label class="control-label col-md-3">Goal</label>
		<div class="col-md-9">
			{{ Form::select('goal', $goals, null, ['class' => 'form-control input-lg']) }}
		</div>
	</div>

	{{ Form::hidden('lesson', $lesson->id) }}

	<div class="form-group">
		<div class="col-md-9 col-md-offset-3">
			<div class="visible-xs visible-sm">
				{{ Form::button('Update', ['type' => 'submit', 'class' => 'btn btn-primary btn-lg btn-block']) }}
			</div>
			<div class="visible-md visible-lg">
				{{ Form::button('Update', ['type' => 'submit', 'class' => 'btn btn-primary btn-lg']) }}
			</div>
		</div>
	</div>
{{ Form::close() }}