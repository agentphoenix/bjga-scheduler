<p>Are you sure you want to remove the <strong>{{ Date::createFromFormat('Y-m-d', $ex->date)->format('l F dS Y') }}</strong> exception?</p>

{{ Form::open(array('route' => array('admin.staff.destroyException', $ex->id), 'method' => 'delete')) }}
	<div class="visible-lg">
		{{ Form::button("Remove", array('type' => 'submit', 'class' => 'btn btn-danger')) }}
	</div>
	<div class="hidden-lg">
		{{ Form::button("Remove", array('type' => 'submit', 'class' => 'btn btn-lg btn-block btn-danger')) }}
	</div>
{{ Form::close() }}