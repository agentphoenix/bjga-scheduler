{{ Form::open(array('route' => array('admin.user.update', $user->id), 'method' => 'put')) }}
	<div class="row">
		<div class="col-lg-9">
			<div class="form-group">
				<label class="control-label">Old Password</label>
				{{ Form::password('password_old', array('class' => 'form-control')) }}
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-9">
			<div class="form-group">
				<label class="control-label">New Password</label>
				{{ Form::password('password', array('class' => 'form-control')) }}
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-9">
			<div class="form-group">
				<label class="control-label">Confirm New Password</label>
				{{ Form::password('password_confirm', array('class' => 'form-control')) }}
			</div>
		</div>
	</div>

	{{ Form::hidden('formAction', 'password') }}

	<div class="visible-lg">
		{{ Form::button("Update", array('type' => 'submit', 'class' => 'btn btn-lg btn-primary')) }}
	</div>
	<div class="hidden-lg">
		{{ Form::button("Update", array('type' => 'submit', 'class' => 'btn btn-lg btn-block btn-primary')) }}
	</div>
{{ Form::close() }}