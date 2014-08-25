@extends('layouts.master')

@section('title')
	Report Center - Unused Credits
@stop

@section('content')
	<h1>Unused Credits</h1>

	<div class="visible-md visible-lg">
		<div class="btn-toolbar">
			<div class="btn-group">
				<a href="{{ URL::route('admin.reports.index') }}" class="btn btn-sm btn-default icn-size-16">{{ $_icons['back'] }}</a>
			</div>
		</div>
	</div>
	<div class="visible-xs visible-sm">
		<div class="row">
			<div class="col-xs-6 col-sm-3">
				<p><a href="{{ URL::route('admin.reports.index') }}" class="btn btn-lg btn-block btn-default icn-size-16">{{ $_icons['back'] }}</a></p>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-6">
			<h2>Monetary Credits</h2>

			@if (array_key_exists('money', $credits))
				<div class="data-table data-table-bordered data-table-striped">
				@foreach ($credits['money'] as $c)
					<div class="row">
						<div class="col-sm-9 col-md-12 col-lg-9">
							<p class="lead"><strong>{{ $c['user']->present()->name }}</strong></p>
						</div>
						<div class="col-sm-3 col-md-12 col-lg-3">
							<p>{{ $c['credit'] }}</p>
						</div>
					</div>
				@endforeach
				</div>
			@else
				{{ alert('warning', "No unused monetary credits.") }}
			@endif
		</div>

		<div class="col-md-6">
			<h2>Time Credits</h2>

			@if (array_key_exists('time', $credits))
				<div class="data-table data-table-bordered data-table-striped">
				@foreach ($credits['time'] as $c)
					<div class="row">
						<div class="col-sm-9 col-md-12 col-lg-9">
							<p class="lead"><strong>{{ $c['user']->present()->name }}</strong></p>
						</div>
						<div class="col-sm-3 col-md-12 col-lg-3">
							<p>{{ $c['credit'] }}</p>
						</div>
					</div>
				@endforeach
				</div>
			@else
				{{ alert('warning', "No unused time credits.") }}
			@endif
		</div>
	</div>
@stop