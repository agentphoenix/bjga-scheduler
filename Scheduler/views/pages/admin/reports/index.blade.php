@extends('layouts.master')

@section('title')
	Report Center
@stop

@section('content')
	<h1>Report Center</h1>

	<div class="hidden-xs">
		<div class="btn-toolbar">
			<div class="btn-group">
				<a href="{{ URL::route('admin') }}" class="btn btn-sm btn-default icn-size-16">{{ $_icons['back'] }}</a>
			</div>
		</div>
	</div>
	<div class="visible-xs">
		<div class="row">
			<div class="col-xs-12 col-sm-6">
				<p><a href="{{ URL::route('admin') }}" class="btn btn-lg btn-block btn-default icn-size-16">{{ $_icons['back'] }}</a></p>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-6 col-md-6 col-lg-6">
			<div class="text-center text-success icn-size-96">{{ $_icons['calendar'] }}</div>
			<p><a href="{{ URL::route('admin.reports.monthly') }}" class="btn btn-lg btn-default btn-block">Monthly Report</a></p>
		</div>

		<div class="col-sm-6 col-md-6 col-lg-6">
			<div class="text-center text-success icn-size-96">{{ $_icons['warning'] }}</div>
			<p><a href="{{ URL::route('admin.reports.unpaid') }}" class="btn btn-lg btn-default btn-block">Unpaid Services</a></p>
		</div>
	</div>
@stop