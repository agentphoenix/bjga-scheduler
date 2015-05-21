<p>Are you sure you want to remove this comment? This cannot be undone!</p>

{{ Form::open(['route' => ['admin.conversation.destroy', $comment->id], 'method' => 'delete']) }}
	<div class="form-group">
		<div class="visible-xs visible-sm">
			{{ Form::button("Remove Comment", ['type' => 'submit', 'class' => 'btn btn-danger btn-lg btn-block']) }}
		</div>
		<div class="visible-md visible-lg">
			{{ Form::button("Remove Comment", ['type' => 'submit', 'class' => 'btn btn-danger btn-lg']) }}
		</div>
	</div>
{{ Form::close() }}