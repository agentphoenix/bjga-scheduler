<p>Select a date and the times you're available for that date then click Submit.</p>

{{ Form::open(array('route' => array('admin.staff.update', $user->staff->id), 'method' => 'put')) }}
	<div class="row">
		<div class="col-lg-4">
			<div class="form-group">
				<label class="label-control">Date</label>
				{{ Form::text('date', null, array('class' => 'form-control js-datepicker')) }}
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<div class="form-group">
				<label class="label-control">Times</label>
				<div class="row">
					@foreach ($times as $value => $time)
						<div class="col-lg-4">
							<label>{{ Form::checkbox('times[]', $value) }} {{ $time }}</label>
						</div>
					@endforeach
				</div>
			</div>
		</div>
	</div>

	{{ Form::hidden('formAction', 'exceptions') }}

	<div class="visible-lg">
		{{ Form::button("Submit", array('type' => 'submit', 'class' => 'btn btn-primary')) }}
	</div>
	<div class="hidden-lg">
		{{ Form::button("Submit", array('type' => 'submit', 'class' => 'btn btn-lg btn-block btn-primary')) }}
	</div>
{{ Form::close() }}