<p>Are you sure you want to remove this schedule block?</p>

{{ Form::open(array('route' => array('admin.staff.block.destroy', $id), 'method' => 'delete')) }}
	<div class="visible-md visible-lg">
		{{ Form::submit("Remove Schedule Block", array('class' => 'btn btn-lg btn-danger')) }}
	</div>
	<div class="visible-xs visible-sm">
		{{ Form::submit("Remove Schedule Block", array('class' => 'btn btn-lg btn-block btn-danger')) }}
	</div>
{{ Form::close() }}