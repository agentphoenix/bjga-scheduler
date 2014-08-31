{{ Form::open(array('route' => array('ajax.emailUser'))) }}
	<div class="row">
		<div class="col-xs-2 col-sm-1 col-md-1 col-lg-1">
			<strong>To:</strong>
		</div>
		<div class="col-xs-10 col-sm-11 col-md-11 col-lg-11">
			<div class="form-group">
				<nobr><span class="label label-default">{{ $user->name }}</span></nobr>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<div class="form-group">
				<label class="control-label">Subject</label>
				<p class="text-danger">Notice of Unpaid Service</p>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<div class="form-group">
				<label class="control-label">Message</label>
				{{ Form::textarea('message', null, array('class' => 'form-control')) }}
			</div>
		</div>
	</div>

	{{ Form::hidden('subject', "Notice of Unpaid Service") }}
	{{ Form::hidden('recipients', $user->email) }}
	{{ Form::hidden('redirect', $redirect) }}

	<div class="visible-md visible-lg">
		{{ Form::submit("Send Email", array('class' => 'btn btn-lg btn-primary')) }}
	</div>
	<div class="visible-xs visible-sm">
		{{ Form::submit("Send Email", array('class' => 'btn btn-lg btn-block btn-primary')) }}
	</div>
{{ Form::close() }}