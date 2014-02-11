@extends('layouts.master')

@section('title')
	Edit Staff Member
@stop

@section('content')
	<h1>Edit Staff Member <small>{{ $staff->user->name }}</small></h1>

	@if ($_currentUser->access() > 1)
		<div class="visible-lg">
			<div class="btn-toolbar">
				<div class="btn-group">
					<a href="{{ URL::route('admin.staff.index') }}" class="btn btn-sm btn-default icn-size-16">{{ $_icons['back'] }}</a>
				</div>
				<div class="btn-group">
					<a href="{{ URL::route('admin.user.edit', array($_currentUser->id)) }}" class="btn btn-sm btn-default icn-size-16-with-text">Edit My User Account</a>
				</div>
				<div class="btn-group">
					<a href="{{ URL::route('admin.staff.block') }}" class="btn btn-sm btn-default icn-size-16-with-text">Manage Schedule Blocks</a>
				</div>
			</div>
		</div>
		<div class="hidden-lg">
			<div class="row">
				<div class="col-xs-12 col-sm-6">
					<p><a href="{{ URL::route('admin.staff.index') }}" class="btn btn-block btn-lg btn-default icn-size-16">{{ $_icons['back'] }}</a></p>
				</div>
				<div class="col-xs-12 col-sm-6">
					<p><a href="{{ URL::route('admin.user.edit', array($_currentUser->id)) }}" class="btn btn-block btn-lg btn-default icn-size-16">Edit User Account</a></p>
				</div>
				<div class="col-xs-12 col-sm-6">
					<p><a href="{{ URL::route('admin.staff.block') }}" class="btn btn-default icn-size-16-with-text">Manage Schedule Blocks</a></p>
				</div>
			</div>
		</div>
	@endif

	{{ Form::model($staff, array('route' => array('admin.staff.update', $staff->id), 'method' => 'put')) }}
		<div class="row">
			<div class="col-lg-6">
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
								{{ Form::select('access', array('1' => 'Level 1', '2' => 'Level 2', '3' => 'Level 3'), null, array('class' => 'form-control input-with-feedback')) }}
								{{ $errors->first('access', '<p class="help-block">:message</p>') }}
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-lg-6">
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
			</div>

			<div class="col-lg-6">
				<div class="panel panel-default">
					<div class="panel-heading"><h3 class="panel-title">Regular Schedule</h3></div>
					<div class="panel-body">
						<p>Enter your schedule for each day of the week. If you have no availability on a day, simply leave it blank. Times should be in a 24-hour format (e.g. 18:00).</p>

						<hr>

						<div class="row">
							<div class="col-lg-12">
								@for ($d = 0; $d <=6; $d++)
									<div class="row">
										<div class="col-sm-5 col-lg-3"><strong>{{ $days[$d] }}</strong></div>
										<div class="col-sm-7 col-lg-9">
											<div class="form-group">
												{{ Form::text('schedule['.$d.']', $schedule->filter(function($s) use ($d){ return $s->day == $d; })->first()->availability, array('class' => 'form-control')) }}
											</div>
										</div>
									</div>
								@endfor
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<div class="visible-lg">
					{{ Form::button('Submit', array('type' => 'submit', 'class' => 'btn btn-lg btn-primary')) }}
				</div>
				<div class="hidden-lg">
					{{ Form::button('Submit', array('type' => 'submit', 'class' => 'btn btn-lg btn-block btn-primary')) }}
				</div>
			</div>
		</div>
	{{ Form::close() }}
@stop