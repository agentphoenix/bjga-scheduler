@extends('layouts.master')

@section('title')
	Report Center
@stop

@section('content')
	<h1>Report Center</h1>

	<div class="row">
		<div class="col-md-4 col-lg-4">
			<div class="text-center text-success icn-size-96">{{ $_icons['calendar'] }}</div>
			<p><a href="{{ route('admin.reports.monthly') }}" class="btn btn-lg btn-primary btn-block">Monthly Report</a></p>
		</div>

		<div class="col-md-4 col-lg-4">
			<div class="text-center text-danger icn-size-96">{{ $_icons['warning'] }}</div>
			<p><a href="{{ route('admin.reports.unpaid') }}" class="btn btn-lg btn-danger btn-block">Unpaid Services</a></p>
		</div>

		<div class="col-md-4 col-lg-4">
			<div class="text-center text-info icn-size-96">{{ $_icons['credit'] }}</div>
			<p><a href="{{ route('admin.reports.credits') }}" class="btn btn-lg btn-info btn-block">Unused Credits</a></p>
		</div>
	</div>
@stop