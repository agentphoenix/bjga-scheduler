<p>Are you sure you want to delete the user <strong>{{ $user->name }}</strong>? This action is permanent and cannot be undone!</p>

{{ Form::open(array('route' => array('admin.user.destroy', $user->id), 'method' => 'delete')) }}
	<div class="visible-lg">
		{{ Form::submit("Delete User", array('class' => 'btn btn-lg btn-danger')) }}
	</div>
	<div class="hidden-lg">
		{{ Form::submit("Delete User", array('class' => 'btn btn-lg btn-block btn-danger')) }}
	</div>
{{ Form::close() }}