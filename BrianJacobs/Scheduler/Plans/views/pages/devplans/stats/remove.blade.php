<p>Are you sure you want to remove these {{ $stat->type }} stats? This cannot be undone!</p>

{{ Form::open(['route' => ['stats.destroy', $stat->id], 'method' => 'delete']) }}
	<div class="form-group">
		<div class="visible-xs visible-sm">
			{{ Form::button("Remove Stats", ['type' => 'submit', 'class' => 'btn btn-danger btn-lg btn-block']) }}
		</div>
		<div class="visible-md visible-lg">
			{{ Form::button("Remove Stats", ['type' => 'submit', 'class' => 'btn btn-danger btn-lg']) }}
		</div>
	</div>
{{ Form::close() }}