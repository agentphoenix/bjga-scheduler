{{ Form::open(['route' => ['plan.update', $plan->id], 'method' => 'put', 'class' => 'form-horizontal']) }}
	<div class="form-group">
		<label class="control-label col-md-2">Student</label>
		<div class="col-md-8">
			<p class="form-control-static">{{ $plan->user->present()->name }}</p>
		</div>
	</div>

	<h3>Add an Instructor</h3>

	<div class="form-group">
		<label class="control-label col-md-2">Instructor</label>
		<div class="col-md-8">
			{{ Form::select('instructor', $staff, null, ['class' => 'form-control input-lg']) }}
		</div>
	</div>

	<div class="form-group">
		<div class="col-md-8 col-md-offset-2">
			<div class="visible-xs visible-sm">
				{{ Form::button("Add Instructor", ['type' => 'submit', 'class' => 'btn btn-primary btn-lg btn-block']) }}
			</div>
			<div class="visible-md visible-lg">
				{{ Form::button("Add Instructor", ['type' => 'submit', 'class' => 'btn btn-primary btn-lg']) }}
			</div>
		</div>
	</div>
{{ Form::close() }}

<hr>

<h3>Development Plan {{ Str::plural('Instructor', $plan->instructors->count()) }}</h3>

<div class="data-table data-table-bordered data-table-striped">
@foreach ($plan->instructors as $instructor)
	<div class="row">
		<div class="col-md-9">
			<p class="lead">{{ $instructor->user->present()->name }}</p>
		</div>
		<div class="col-md-3">
			<p><a href="#" class="btn btn-danger btn-lg btn-block js-removeInstructor" data-instructor="{{ $instructor->id }}" data-plan="{{ $plan->id }}">Remove</a></p>
		</div>
	</div>
@endforeach
</div>