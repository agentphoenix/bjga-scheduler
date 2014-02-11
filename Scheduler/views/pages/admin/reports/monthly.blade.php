@extends('layouts.master')

@section('title')
	Report Center - Monthly Report
@stop

@section('content')
	<h1>Monthly Report</h1>

	<div class="hidden-xs">
		<div class="btn-toolbar">
			<div class="btn-group">
				<a href="{{ URL::route('admin.reports.index') }}" class="btn btn-sm btn-default icn-size-16">{{ $_icons['back'] }}</a>
			</div>
		</div>
	</div>
	<div class="visible-xs">
		<div class="row">
			<div class="col-xs-12 col-sm-6">
				<p><a href="{{ URL::route('admin.reports.index') }}" class="btn btn-lg btn-block btn-default icn-size-16">{{ $_icons['back'] }}</a></p>
			</div>
		</div>
	</div>
@stop