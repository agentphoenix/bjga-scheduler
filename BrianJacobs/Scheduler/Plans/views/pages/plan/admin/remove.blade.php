<p>Are you sure you want to remove {{ $plan->user->name }}'s development plan? This will remove the plan and any goals, conversations, and statistics stored for the student and cannot be undone!</p>

{{ Form::open(['route' => ['admin.plan.destroy', $plan->id], 'method' => 'delete']) }}
	<div class="form-group">
		<div class="visible-xs visible-sm">
			{{ Form::button("Remove Plan", ['type' => 'submit', 'class' => 'btn btn-danger btn-lg btn-block']) }}
		</div>
		<div class="visible-md visible-lg">
			{{ Form::button("Remove Plan", ['type' => 'submit', 'class' => 'btn btn-danger btn-lg']) }}
		</div>
	</div>
{{ Form::close() }}