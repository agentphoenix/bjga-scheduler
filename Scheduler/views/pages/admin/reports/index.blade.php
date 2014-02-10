@extends('layouts.master')

@section('title')
	Report Center
@stop

@section('content')
	<h1>Report Center</h1>

	<div class="hidden-xs">
		<div class="btn-toolbar">
			<div class="btn-group">
				<a href="{{ URL::route('admin') }}" class="btn btn-default icn-size-16">{{ $_icons['back'] }}</a>
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
			<div class="lg-btn-spacing">
				<a href="{{ URL::route('admin.reports.monthly') }}" class="btn btn-lg btn-default btn-block">
					<div class="icn-size-96">{{ $_icons['calendar'] }}</div>
					Monthly Report
				</a>
			</div>
		</div>

		<div class="col-sm-6 col-md-6 col-lg-6">
			<div class="lg-btn-spacing">
				<a href="{{ URL::route('admin.reports.unpaid') }}" class="btn btn-lg btn-default btn-block">
					<div class="icn-size-96">{{ $_icons['warning'] }}</div>
					Unpaid Services
				</a>
			</div>
		</div>
	</div>
@stop