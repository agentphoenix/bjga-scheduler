<p>Are you sure you'd like to remove this schedule block?</p>

{{ Form::open(array('route' => array('admin.staff.block.destroy', $id), 'method' => 'delete')) }}
	<div class="visible-lg">
		{{ Form::submit("Remove Block", array('class' => 'btn btn-lg btn-danger')) }}
	</div>
	<div class="hidden-lg">
		{{ Form::submit("Remove Block", array('class' => 'btn btn-lg btn-block btn-danger')) }}
	</div>
{{ Form::close() }}