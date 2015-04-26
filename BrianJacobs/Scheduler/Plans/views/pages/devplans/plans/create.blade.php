{{ Form::open(['route' => 'admin.plan.store', 'class' => 'form-horizontal']) }}
	<div class="form-group">
		<label class="control-label col-md-2">User</label>
		<div class="col-md-8">
			{{ Form::select('user_id', $users, null, ['class' => 'form-control input-lg']) }}
		</div>
	</div>

	<div class="form-group">
		<div class="col-md-8 col-md-offset-2">
			<div class="visible-xs visible-sm">
				{{ Form::button("Add Plan", ['type' => 'submit', 'class' => 'btn btn-primary btn-lg btn-block']) }}
			</div>
			<div class="visible-md visible-lg">
				{{ Form::button("Add Plan", ['type' => 'submit', 'class' => 'btn btn-primary btn-lg']) }}
			</div>
		</div>
	</div>
{{ Form::close() }}