<div class="row">
	<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
		<p><strong>Service</strong></p>
	</div>
	<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
		<p>{{ $appointment->service->name }}</p>
	</div>
</div>
<div class="row">
	<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
		<p><strong>Start</strong></p>
	</div>
	<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
		<p>{{ $appointment->start->format(Config::get('bjga.dates.full')) }}</p>
	</div>
</div>
<div class="row">
	<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
		<p><strong>End</strong></p>
	</div>
	<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
		<p>{{ $appointment->end->format(Config::get('bjga.dates.full')) }}</p>
	</div>
</div>

<hr>

{{ Form::open(array('route' => array('book.withdraw'))) }}
	@if ($appointment->service->isRecurring())
		<div class="row">
			<div class="col-lg-12">
				<div class="form-group">
					<div>
						<label class="checkbox-inline text-sm">
							{{ Form::checkbox('cancel_all', 1, false) }}
							@if ($appointment->service->isLesson())
								Withdraw from the entire series
							@else
								Withdraw from the entire program
							@endif
						</label>
					</div>
				</div>
			</div>
		</div>
	@endif

	<div class="row">
		<div class="col-lg-12">
			<div class="form-group">
				<label class="control-label">Please provide a brief explanation for the cancellation</label>
				{{ Form::textarea('reason', null, array('class' => 'form-control', 'rows' => 8)) }}
			</div>
		</div>
	</div>

	{{ Form::hidden('appointment', $appointment->id) }}

	<div class="visible-md visible-lg">
		{{ Form::submit("Cancel Appointment", array('class' => 'btn btn-lg btn-danger')) }}
	</div>
	<div class="visible-xs visible-sm">
		{{ Form::submit("Cancel Appointment", array('class' => 'btn btn-lg btn-block btn-danger')) }}
	</div>
{{ Form::close() }}