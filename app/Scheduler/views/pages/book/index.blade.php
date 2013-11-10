@extends('layouts.master')

@section('title')
	Book Now
@endsection

@section('content')
	<h1>Book an Appointment</h1>

	<div class="row">
		<div class="col-md-4 col-lg-4">
			<div class="well text-center">
				<div class="icn icn-size-96" data-icon="l"></div>
				<a href="{{ URL::route('book.lesson') }}" class="btn btn-primary btn-block">Private Instruction</a>
			</div>
		</div>

		<div class="col-md-4 col-lg-4">
			<div class="well text-center">
				<div class="icn icn-size-96" data-icon="g"></div>
				<a href="#" class="btn btn-primary btn-block">Programs</a>
			</div>
		</div>

		<div class="col-md-4 col-lg-4">
			<div class="well text-center">
				<div class="icn icn-size-96" data-icon="e"></div>
				<a href="#" class="btn btn-primary btn-block">Events</a>
			</div>
		</div>

		<div class="col-md-4 col-lg-4">
			<div class="well text-center">
				<div class="icn icn-size-96" data-icon="t"></div>
				<a href="#" class="btn btn-primary btn-block">Teams</a>
			</div>
		</div>

		<div class="col-md-4 col-lg-4">
			<div class="well text-center">
				<div class="icn icn-size-96" data-icon="s"></div>
				<a href="#" class="btn btn-primary btn-block">Schools &amp; Clinics</a>
			</div>
		</div>

		<div class="col-md-4 col-lg-4">
			<div class="well text-center">
				<div class="icn icn-size-96" data-icon="f"></div>
				<a href="#" class="btn btn-primary btn-block">Club Fitting</a>
			</div>
		</div>
	</div>
@endsection