<p>Are you sure you want to remove the <strong>{{ $goal->present()->title }}</strong> goal from {{ $goal->plan->user->name }}'s development plan? This will remove the goal and any comments and statistics stored for the goal. This cannot be undone!</p>

{{ Form::open(['route' => ['goal.destroy', $goal->id], 'method' => 'delete']) }}
	<div class="form-group">
		<div class="visible-xs visible-sm">
			{{ Form::button("Remove Goal", ['type' => 'submit', 'class' => 'btn btn-danger btn-lg btn-block']) }}
		</div>
		<div class="visible-md visible-lg">
			{{ Form::button("Remove Goal", ['type' => 'submit', 'class' => 'btn btn-danger btn-lg']) }}
		</div>
	</div>
{{ Form::close() }}