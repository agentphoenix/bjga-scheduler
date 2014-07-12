@extends('layouts.master')

@section('title')
	Credits
@stop

@section('content')
	<h1>Credits</h1>

	<div class="visible-md visible-lg">
		<div class="btn-toolbar">
			<div class="btn-group">
				<a href="{{ URL::route('admin.credits.create') }}" class="btn btn-sm btn-primary icn-size-16">{{ $_icons['add'] }}</a>
			</div>
		</div>
	</div>
	<div class="visible-xs visible-sm">
		<div class="row">
			<div class="col-xs-6 col-sm-3">
				<p><a href="{{ URL::route('admin.credits.create') }}" class="btn btn-block btn-lg btn-primary icn-size-16">{{ $_icons['add'] }}</a></p>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-6 col-lg-4">
			<div class="form-group">
				{{ Form::text('search', null, array('placeholder' => 'Search for credits', 'class' => 'form-control search-control', 'id' => 'searchCredits')) }}
			</div>
		</div>
	</div>

	@if ($credits->count() > 0)
		<div class="data-table data-table-striped data-table-bordered" id="creditsTable">
		@foreach ($credits as $c)
			<div class="row">
				<div class="col-sm-6 col-lg-9">
					<p><strong>{{ $c->code }}</strong></p>
				</div>
				<div class="col-sm-6 col-lg-3">
					<div class="visible-md visible-lg">
						<div class="btn-toolbar pull-right">
							<div class="btn-group">
								<a href="{{ URL::route('admin.staff.schedule', array($c->id)) }}" class="btn btn-sm btn-default icn-size-16 js-tooltip-top" data-title="Staff Schedule">{{ $_icons['calendar'] }}</a>
							</div>
							<div class="btn-group">
								<a href="{{ URL::route('admin.staff.edit', array($c->id)) }}" class="btn btn-sm btn-default icn-size-16 js-tooltip-top" data-title="Edit Staff Member">{{ $_icons['edit'] }}</a>
							</div>
							<div class="btn-group">
								<a href="#" class="btn btn-sm btn-danger icn-size-16 js-staff-action js-tooltip-top" data-title="Remove Staff Member" data-action="delete" data-id="{{ $c->id }}">{{ $_icons['remove'] }}</a>
							</div>
						</div>
					</div>
					<div class="visible-xs visible-sm">
						<div class="row">
							<div class="col-sm-4">
								<p><a href="{{ URL::route('admin.staff.schedule', array($c->id)) }}" class="btn btn-lg btn-block btn-default icn-size-16">Staff Schedule</a></p>
							</div>
							<div class="col-sm-4">
								<p><a href="{{ URL::route('admin.staff.edit', array($c->id)) }}" class="btn btn-block btn-lg btn-default icn-size-16">Edit Staff Member</a></p>
							</div>
							<div class="col-sm-4">
								<p><a href="#" class="btn btn-block btn-lg btn-danger icn-size-16 js-staff-action" data-action="delete" data-id="{{ $c->id }}">Remove Staff Member</a></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		@endforeach
		</div>
	@else
		{{ partial('common/alert', ['type' => 'warning', 'content' => "No credits found."]) }}
	@endif
@stop