@extends('layouts.master')

@section('title')
	Report Center
@stop

@section('content')
	<h1>Report Center</h1>

	<div class="row">
		<div class="col-sm-6 col-md-6 col-lg-6">
			<div class="text-center text-success icn-size-96">{{ $_icons['calendar'] }}</div>
			<p><a href="{{ URL::route('admin.reports.monthly') }}" class="btn btn-lg btn-primary btn-block">Monthly Report</a></p>
		</div>

		<div class="col-sm-6 col-md-6 col-lg-6">
			<div class="text-center text-danger icn-size-96">{{ $_icons['warning'] }}</div>
			<p><a href="{{ URL::route('admin.reports.unpaid') }}" class="btn btn-lg btn-danger btn-block">Unpaid Services</a></p>
		</div>
	</div>
@stop