<div id="reportProblem" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h3 class="modal-title">Report a Problem</h3>
			</div>

			<div class="modal-body">
				<p>Found a problem? Drop us a line and let us know and we'll take a look. Make sure to be as specific as you can about what happened, any errors you received, what browser you're using, and any other details think might be important.</p>

				{{ Form::open(array('route' => 'report')) }}
					<div class="row">
						<div class="col-lg-12">
							<div class="form-group">
								{{ Form::textarea('content', null, array('class' => 'form-control', 'placeholder' => "Describe the problem")) }}
							</div>
						</div>
					</div>
					
					<div class="visible-md visible-lg">
						{{ Form::submit("Send Feedback", array('class' => 'btn btn-lg btn-primary')) }}
					</div>
					<div class="visible-xs visible-sm">
						{{ Form::submit("Send Feedback", array('class' => 'btn btn-lg btn-block btn-primary')) }}
					</div>
				{{ Form::close() }}
			</div>
		</div>
	</div>
</div>