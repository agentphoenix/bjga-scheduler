<p>Are you sure you want to remove <strong>{{ $staff->user->name }}</strong> from the staff?</p>

{{ Form::open(array('route' => array('admin.staff.destroy', $staff->id), 'method' => 'delete')) }}
	<div class="visible-lg">
		{{ Form::button("Remove", array('type' => 'submit', 'class' => 'btn btn-danger')) }}
	</div>
	<div class="hidden-lg">
		{{ Form::button("Remove", array('type' => 'submit', 'class' => 'btn btn-lg btn-block btn-danger')) }}
	</div>
{{ Form::close() }}