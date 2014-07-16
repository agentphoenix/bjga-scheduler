@extends('layouts.master')

@section('title')
	Manage Appointments
@stop

@section('content')
	<h1>Manage Appointments</h1>

	<div class="row">
		<div class="col-sm-4 col-md-4 col-lg-4">
			<div class="text-center text-success icn-size-96">{{ $_icons['add'] }}</div>
			<p><a href="{{ URL::route('admin.appointment.create') }}" class="btn btn-lg btn-primary btn-block">Add Appointment</a></p>
		</div>
		<div class="col-sm-4 col-md-4 col-lg-4">
			<div class="text-center text-success icn-size-96">{{ $_icons['users'] }}</div>
			<p><a href="{{ URL::route('admin.appointment.user') }}" class="btn btn-lg btn-primary btn-block">Appointments by Student</a></p>
		</div>
		<div class="col-sm-4 col-md-4 col-lg-4">
			<div class="text-center text-success icn-size-96">{{ $_icons['recur'] }}</div>
			<p><a href="{{ URL::route('admin.appointment.recurring.index') }}" class="btn btn-lg btn-primary btn-block">Recurring Lessons</a></p>
		</div>
	</div>
@stop