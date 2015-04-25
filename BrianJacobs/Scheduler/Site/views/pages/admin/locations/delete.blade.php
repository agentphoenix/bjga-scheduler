<p>Are you sure you want to delete <strong>{{ $location->present()->name }}</strong>? This action is permanent and cannot be undone!</p>

{{ Form::open(['route' => ['admin.locations.destroy', $location->id], 'method' => 'delete']) }}
	<div class="row">
		<div class="col-xs-12">
			<div class="visible-md visible-lg">
				{{ Form::submit('Delete', array('class' => 'btn btn-lg btn-danger')) }}
			</div>
			<div class="visible-xs visible-sm">
				{{ Form::submit('Delete', array('class' => 'btn btn-lg btn-block btn-danger')) }}
			</div>
		</div>
	</div>
{{ Form::close() }}