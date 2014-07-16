@extends('layouts.master')

@section('title')
	Appointments By Student
@stop

@section('content')
	<h1>Appointments By Student</h1>

	<div class="visible-md visible-lg">
		<div class="btn-toolbar">
			<div class="btn-group">
				<a href="{{ URL::route('admin.appointment.index') }}" class="btn btn-sm btn-default icn-size-16">{{ $_icons['back'] }}</a>
			</div>
		</div>
	</div>
	<div class="visible-xs visible-sm">
		<div class="row">
			<div class="col-xs-6 col-sm-3">
				<p><a href="{{ URL::route('admin.appointment.index') }}" class="btn btn-lg btn-block btn-default icn-size-16">{{ $_icons['back'] }}</a></p>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-6 col-md-6 col-lg-4">
			{{ Form::text('search', null, array('placeholder' => 'Search for users', 'class' => 'form-control search-control', 'id' => 'searchUsers')) }}
		</div>
	</div>

	<div class="data-table data-table-striped data-table-bordered" id="usersTable">
	@foreach ($users as $user)
		@if ($user->appointments->count() > 0)
			<div class="row">
				<div class="col-sm-6 col-md-6 col-lg-8">
					<p><strong>{{ $user->name }}</strong></p>
					<p class="text-muted text-sm">{{ $user->email }}</p>
				</div>
				<div class="col-sm-6 col-md-6 col-lg-4">
					<div class="visible-md visible-lg">
						<div class="btn-toolbar pull-right">
							<div class="btn-group">
								<a href="{{ URL::route('admin.appointment.history', array($user->id)) }}" class="btn btn-sm btn-default icn-size-16 js-tooltip-top" data-title="Student History">{{ $_icons['schedule'] }}</a>
							</div>
							<div class="btn-group">
								<a href="{{ URL::route('admin.appointment.user', array($user->id)) }}" class="btn btn-sm btn-default icn-size-16 js-tooltip-top" data-title="Student's Schedule">{{ $_icons['calendar'] }}</a>
							</div>
						</div>
					</div>
					<div class="visible-xs visible-sm">
						<div class="row">
							<div class="col-sm-6">
								<p><a href="{{ URL::route('admin.appointment.history', array($user->id)) }}" class="btn btn-lg btn-block btn-default icn-size-16">Student History</a></p>
							</div>
							<div class="col-sm-6">
								<p><a href="{{ URL::route('admin.appointment.user', array($user->id)) }}" class="btn btn-block btn-lg btn-default icn-size-16">Student's Schedule</a></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		@endif
	@endforeach
	</div>
@stop

@section('scripts')
	{{ HTML::script('js/jquery.quicksearch.min.js') }}
	<script>

		$('#searchUsers').quicksearch('#usersTable > div', {
			hide: function()
			{
				$(this).addClass('hide');
			},
			show: function()
			{
				$(this).removeClass('hide');
			}
		});

	</script>
@stop