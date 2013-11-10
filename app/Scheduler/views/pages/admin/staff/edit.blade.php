@extends('layouts.master')

@section('title')
	Edit Staff Member
@endsection

@section('content')
	<h1>Edit Staff Member <small>{{ $staff->user->name }}</small></h1>

	@if ($_currentUser->access() > 1)
		<div class="visible-lg">
			<div class="btn-toolbar">
				<div class="btn-group">
					<a href="{{ URL::route('admin.staff.index') }}" class="btn btn-default icn-size-16">{{ $_icons['back'] }}</a>
				</div>
			</div>
		</div>
		<div class="hidden-lg">
			<div class="row">
				<div class="col-xs-12 col-sm-6">
					<p><a href="{{ URL::route('admin.staff.index') }}" class="btn btn-block btn-lg btn-default icn-size-16">{{ $_icons['back'] }}</a></p>
				</div>
			</div>
		</div>
	@endif

	{{ Form::model($staff, array('route' => array('admin.staff.update', $staff->id), 'method' => 'put')) }}
		<div class="row">
			<div class="col-lg-6">
				<fieldset>
					<legend>My Info</legend>

					@if ($_currentUser->access() > 1)
						<div class="row">
							<div class="col-lg-8">
								<div class="form-group">
									<label class="label-control">Title</label>
									{{ Form::text('title', null, array('class' => 'form-control')) }}
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-lg-4">
								<div class="form-group{{ ($errors->has('access')) ? ' has-error' : '' }}">
									<label class="label-control">Access Level</label>
									{{ Form::text('access', null, array('class' => 'form-control input-with-feedback')) }}
									{{ $errors->first('access', '<p class="help-block">:message</p>') }}
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-lg-4">
								<div class="form-group">
									<label class="label-control">Available for Instruction</label>
									<div>
										<label class="radio-inline">{{ Form::radio('instruction', (int) true) }} Yes</label>
										<label class="radio-inline">{{ Form::radio('instruction', (int) false) }} No</label>
									</div>
								</div>
							</div>
						</div>
					@endif

					<div class="row">
						<div class="col-lg-12">
							<div class="form-group">
								<label class="label-control">Bio</label>
								{{ Form::textarea('bio', null, array('class' => 'form-control', 'rows' => 8)) }}
							</div>
						</div>
					</div>
				</fieldset>
			</div>

			<div class="col-lg-6">
				<fieldset>
					<legend>Regular Schedule</legend>

					<p>Enter your schedule for each day of the week. If you have no availability on a day, simply leave it blank. Times should be in a 24-hour format (e.g. 18:00).</p>

					<div class="visible-lg">
						<p><a href="#" class="btn btn-default">Enter Schedule Exception</a></p>
					</div>
					<div class="hidden-lg">
						<p><a href="#" class="btn btn-lg btn-block btn-default">Enter Schedule Exception</a></p>
					</div>

					<hr>

					@for ($d = 0; $d <=6; $d++)
						<div class="row">
							<div class="col-sm-5 col-lg-3"><strong>{{ $days[$d] }}</strong></div>
							<div class="col-sm-7 col-lg-9">
								<div class="form-group">
									{{ Form::text('schedule['.$d.']', $schedule->filter(function($s) use($d){ return $s->day == $d; })->first()->availability, array('class' => 'form-control')) }}
								</div>
							</div>
						</div>
					@endfor
				</fieldset>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<div class="visible-lg">
					{{ Form::button('Submit', array('type' => 'submit', 'class' => 'btn btn-primary')) }}
				</div>
				<div class="hidden-lg">
					{{ Form::button('Submit', array('type' => 'submit', 'class' => 'btn btn-lg btn-block btn-primary')) }}
				</div>
			</div>
		</div>
	{{ Form::close() }}

	{{ modal(array('id' => 'staffExceptions', 'header' => "Set Schedule Exception")) }}
@endsection

@section('scripts')
	<script type="text/javascript">
		
		$('.js-staff-action').on('click', function(e)
		{
			e.preventDefault();

			var action = $(this).data('action');
			var id = $(this).data('id');

			if (action == 'exceptions')
			{
				$('#staffExceptions').modal({
					remote: "{{ URL::to('ajax/staff/exception') }}/" + id
				}).modal('show');
			}
		});

	</script>
@endsection