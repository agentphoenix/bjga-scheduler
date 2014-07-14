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
				<div class="col-sm-4">
					<p>
						<strong class="monospace">{{ $c->code }}</strong>
						
						@if ( ! empty($c->user_id))
							<br><span class="text-muted"><span class="tab-icon">{{ $_icons['user'] }}</span><em>{{ $c->present()->user }}</em></span>
						@endif

						@if ( ! empty($c->email))
							<br><span class="text-muted"><span class="tab-icon tab-icon-down1">{{ $_icons['email'] }}</span><em>{{ $c->present()->email }}</em></span>
						@endif
					</p>

					@if ( ! empty($c->notes))
						<span class="text-info text-sm">{{ $c->present()->notes }}</span>
					@endif
				</div>
				<div class="col-sm-4 col-md-2">
					<p>{{ $c->present()->valueLong }}</p>
				</div>
				<div class="col-sm-4 col-md-3">
					<p>{{ $c->present()->remainingLong }}</p>
				</div>

				<div class="clearfix visible-sm-block"></div>

				<div class="col-md-3">
					<div class="visible-md visible-lg">
						<div class="btn-toolbar pull-right">
							<div class="btn-group">
								<a href="{{ URL::route('admin.credits.edit', array($c->id)) }}" class="btn btn-sm btn-default icn-size-16 js-tooltip-top" data-title="Edit Credit">{{ $_icons['edit'] }}</a>
							</div>

							@if ($c->type == 'time')
								<div class="btn-group">
									<a href="#" class="btn btn-sm btn-danger icn-size-16 js-credit-action js-tooltip-top" data-title="Delete Credit" data-action="delete" data-id="{{ $c->id }}">{{ $_icons['remove'] }}</a>
								</div>
							@endif
						</div>
					</div>
					<div class="visible-xs visible-sm">
						<div class="row">
							<div class="col-sm-6">
								<p><a href="{{ URL::route('admin.credits.edit', array($c->id)) }}" class="btn btn-block btn-lg btn-default icn-size-16">Edit Credit</a></p>
							</div>
							
							@if ($c->type == 'time')
								<div class="col-sm-6">
									<p><a href="#" class="btn btn-block btn-lg btn-danger icn-size-16 js-credit-action" data-action="delete" data-id="{{ $c->id }}">Delete Credit</a></p>
								</div>
							@endif
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

@section('modals')
	{{ modal(['id' => 'deleteCredit', 'header' => "Delete User Credit"]) }}
@stop

@section('scripts')
	<script>
		$(document).on('click', '.js-credit-action', function(e)
		{
			e.preventDefault();

			var action = $(this).data('action');
			var id = $(this).data('id');

			if (action == 'delete')
			{
				$('#deleteCredit').modal({
					remote: "{{ URL::to('admin/credits/delete') }}/" + id
				}).modal('show');
			}
		});
	</script>
@stop