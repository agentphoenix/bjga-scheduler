@extends('layouts.master')

@section('title')
	User Details - {{ $user->name }}
@stop

@section('content')
	<h1>User Details <small>{{ $user->name }}</small></h1>

	<div class="visible-md visible-lg">
		<div class="btn-toolbar">
			<div class="btn-group">
				<a href="{{ route('admin.user.index') }}" class="btn btn-sm btn-default icn-size-16">{{ $_icons['back'] }}</a>
			</div>
		</div>
	</div>
	<div class="visible-xs visible-sm">
		<div class="row">
			<div class="col-xs-12 col-sm-4">
				<p><a href="{{ route('admin.user.index') }}" class="btn btn-block btn-lg btn-default icn-size-16">{{ $_icons['back'] }}</a></p>
			</div>
		</div>
	</div>

	<div class="form-horizontal">
		<div class="form-group">
			<label class="control-label col-md-3">Name</label>
			<div class="col-md-9">
				<p class="form-control-static">{{ $user->present()->name }}</p>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-3">Email Address</label>
			<div class="col-md-9">
				<p class="form-control-static">{{ $user->present()->email }}</p>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-3">Phone Number</label>
			<div class="col-md-9">
				<p class="form-control-static">{{ $user->present()->phone }}</p>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-3">Address</label>
			<div class="col-md-9">
				<p class="form-control-static">{{ $user->present()->address }}</p>
			</div>
		</div>
	</div>
@stop