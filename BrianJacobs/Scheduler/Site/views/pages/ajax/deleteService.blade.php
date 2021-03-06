<p>Are you sure you want to delete the <strong>{{ $service->name }}</strong> service? This will remove the service as well as any staff and user appointments for this service. This action is permanent and cannot be undone!</p>

{{ Form::open(array('route' => array('admin.service.destroy', $service->id), 'method' => 'delete')) }}
	<div class="visible-md visible-lg">
		{{ Form::submit("Remove Service", array('class' => 'btn btn-lg btn-danger')) }}
	</div>
	<div class="visible-xs visible-sm">
		{{ Form::submit("Remove Service", array('class' => 'btn btn-lg btn-block btn-danger')) }}
	</div>
{{ Form::close() }}