@extends('layouts.master')

@section('title')
	Book Now
@endsection

@section('content')
	<h1 class="visible-sm visible-md visible-lg">Book an Appointment</h1>
	<h1 class="visible-xs">Book Now</h1>

	<div class="row">
		<div class="col-md-6 col-lg-6">
			<div class="lg-btn-spacing">
				<a href="{{ URL::route('book.lesson') }}" class="btn btn-lg btn-default btn-block">
					<div class="icn icn-size-96" data-icon="l"></div>
					<span class="text-large">Book a Lesson</span>
				</a>
			</div>
		</div>

		<div class="col-md-6 col-lg-6">
			<div class="lg-btn-spacing">
				<a href="{{ URL::route('book.lesson') }}" class="btn btn-lg btn-default btn-block">
					<div class="icn icn-size-96" data-icon="g"></div>
					<span class="text-large">Find a Program, Event or Clinic</span>
				</a>
			</div>
		</div>

		<!--<div class="col-md-6 col-lg-6">
			<div class="well text-center">
				<div class="icn icn-size-96" data-icon="g"></div>
				<a href="{{ URL::route('book.program') }}" class="btn btn-lg btn-primary btn-block">Programs</a>
			</div>
		</div>

		<div class="col-md-6 col-lg-6">
			<div class="well text-center">
				<div class="icn icn-size-96" data-icon="e"></div>
				<a href="#" class="btn btn-lg btn-primary btn-block">Events</a>
			</div>
		</div>-->

		<!--<div class="col-md-6 col-lg-6">
			<div class="well text-center">
				<div class="icn icn-size-96" data-icon="t"></div>
				<a href="#" class="btn btn-lg btn-primary btn-block">Teams</a>
			</div>
		</div>-->

		<!--<div class="col-md-6 col-lg-6">
			<div class="well text-center">
				<div class="icn icn-size-96" data-icon="s"></div>
				<a href="#" class="btn btn-lg btn-primary btn-block">Schools &amp; Clinics</a>
			</div>
		</div>-->

		<!--<div class="col-md-6 col-lg-6">
			<div class="well text-center">
				<div class="icn icn-size-96" data-icon="f"></div>
				<a href="#" class="btn btn-lg btn-primary btn-block">Club Fitting</a>
			</div>
		</div>-->
	</div>
@endsection