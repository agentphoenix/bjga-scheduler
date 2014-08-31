@extends('layouts.master')

@section('title')
	Manage Appointments
@stop

@section('content')
	<h1>Manage Appointments</h1>

	<div class="row">
		<div class="col-sm-6 col-lg-3">
			<div class="text-center text-success icn-size-96">{{ $_icons['add'] }}</div>
			<p><a href="{{ URL::route('admin.appointment.create') }}" class="btn btn-lg btn-primary btn-block">Add Appointment</a></p>
		</div>
		<div class="col-sm-6 col-lg-3">
			<div class="text-center text-success icn-size-96">{{ $_icons['reject'] }}</div>
			<p><a href="#" class="btn btn-lg btn-primary btn-block js-staff-action" data-action="add">Add Schedule Block</a></p>
		</div>
		<div class="col-sm-6 col-lg-3">
			<div class="text-center text-success icn-size-96">{{ $_icons['users'] }}</div>
			<p><a href="{{ URL::route('admin.appointment.user') }}" class="btn btn-lg btn-primary btn-block">Appointments by Student</a></p>
		</div>
		<div class="col-sm-6 col-lg-3">
			<div class="text-center text-success icn-size-96">{{ $_icons['recur'] }}</div>
			<p><a href="{{ URL::route('admin.appointment.recurring.index') }}" class="btn btn-lg btn-primary btn-block">Recurring Lessons</a></p>
		</div>
	</div>
@stop

@section('modals')
	{{ modal(array('id' => 'addBlock', 'header' => "Create Schedule Block")) }}
@stop

@section('scripts')
	<script>
		$('.js-staff-action').on('click', function(e)
		{
			e.preventDefault();

			var action = $(this).data('action');
			var id = $(this).data('id');

			if (action == 'add')
			{
				$('#addBlock').modal({
					remote: "{{ URL::route('admin.staff.block.create') }}"
				}).modal('show');
			}
		});
	</script>
@stop