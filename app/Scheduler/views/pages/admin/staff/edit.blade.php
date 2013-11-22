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

				<div class="btn-group">
					<a href="{{ URL::route('admin.user.edit', array($_currentUser->id)) }}" class="btn btn-default icn-size-16-with-text">Edit My User Account</a>
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

	<ul class="nav nav-tabs">
		<li class="active"><a href="#myInfo" data-toggle="tab">My Info</a></li>
		<li><a href="#scheduleRegular" data-toggle="tab">Regular Schedule</a></li>
		<li><a href="#scheduleExceptions" data-toggle="tab">Schedule Exceptions</a></li>
	</ul>

	<div class="tab-content">
		<div id="myInfo" class="tab-pane active">
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
		</div>

		<div id="scheduleRegular" class="tab-pane">
			{{ Form::model($staff, array('route' => array('admin.staff.update', $staff->id), 'method' => 'put')) }}
				
				<p>Enter your schedule for each day of the week. If you have no availability on a day, simply leave it blank. Times should be in a 24-hour format (e.g. 18:00).</p>

				<hr>

				<div class="row">
					<div class="col-lg-6">
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
		</div>

		<div id="scheduleExceptions" class="tab-pane">
			@if ($staff->exceptions->count() > 0)
				<div class="btn-toolbar">
					<div class="btn-group">
						<a href="#" class="btn btn-default icn-size-16">{{ $_icons['add'] }}</a>
					</div>
				</div>
				
				<ul class="nav nav-pills">
					<li class="active"><a href="#excUpcoming" data-toggle="pill">Upcoming Exceptions</a></li>
					<li><a href="#excHistory" data-toggle="pill">Exception History</a></li>
				</ul>

				<div class="tab-content">
					<div id="excUpcoming" class="tab-pane active">
						@if ($exceptionsUpcoming->count() > 0)
							<div class="data-table data-table-striped data-table-bordered">
							@foreach ($exceptionsUpcoming as $e)
								<div class="row">
									<div class="col-xs-12 col-sm-12 col-lg-9">
										<p><strong>{{ Date::createFromFormat('Y-m-d', $e->date)->format('l F dS Y') }}</strong></p>
										<p class="text-muted text-small">{{ implode(', ', $e->exceptions) }}</p>
									</div>
									<div class="col-xs-12 col-sm-12 col-lg-3">
										<div class="visible-lg">
											<div class="btn-toolbar pull-right">
												<div class="btn-group">
													<a href="#" class="btn btn-small btn-default icn-size-16">{{ $_icons['edit'] }}</a>
												</div>
												<div class="btn-group">
													<a href="#" class="btn btn-small btn-danger icn-size-16 js-staff-action" data-action="delete" data-id="{{ $e->id }}">{{ $_icons['remove'] }}</a>
												</div>
											</div>
										</div>
										<div class="hidden-lg">
											<div class="row">
												<div class="col-xs-12 col-sm-12">
													<p><a href="#" class="btn btn-block btn-lg btn-default icn-size-16">{{ $_icons['edit'] }}</a></p>
												</div>
												<div class="col-xs-12 col-sm-12">
													<p><a href="#" class="btn btn-block btn-lg btn-danger icn-size-16 js-staff-action" data-action="delete" data-id="{{ $e->id }}">{{ $_icons['remove'] }}</a></p>
												</div>
											</div>
										</div>
									</div>
								</div>
							@endforeach
							</div>
						@else
							<div class="alert alert-warning">No upcoming schedule exceptions found.</div>
						@endif
					</div>

					<div id="excHistory" class="tab-pane">
						@if ($exceptionsHistory->count() > 0)
							<div class="data-table data-table-striped data-table-bordered">
							@foreach ($exceptionsHistory as $e)
								<div class="row">
									<div class="col-xs-12 col-sm-12 col-lg-9">
										<p><strong>{{ Date::createFromFormat('Y-m-d', $e->date)->format('l F dS Y') }}</strong></p>
										<p class="text-muted text-small">{{ implode(', ', $e->exceptions) }}</p>
									</div>
								</div>
							@endforeach
							</div>
						@else
							<div class="alert alert-warning">No schedule exception history found.</div>
						@endif
					</div>
				</div>
			@endif
		</div>
	</div>

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