@extends('layouts.master')

@section('title')
	Staff Schedule
@stop

@section('content')
	<h1>Schedule <small>{{ $staff->user->name }}</small></h1>

	<div class="visible-md visible-lg">
		<div class="btn-toolbar">
			<div class="btn-group">
				<a href="{{ URL::route('admin.staff.index') }}" class="btn btn-sm btn-default icn-size-16">{{ $_icons['back'] }}</a>
			</div>
			<div class="btn-group">
				<a class="btn btn-sm btn-primary icn-size-16 js-staff-action" data-action="add">{{ $_icons['add'] }}</a>
			</div>
		</div>
	</div>
	<div class="visible-xs visible-sm">
		<div class="row">
			<div class="col-xs-6 col-sm-3">
				<p><a href="{{ URL::route('admin.staff.index') }}" class="btn btn-block btn-lg btn-default icn-size-16">{{ $_icons['back'] }}</a></p>
			</div>
			<div class="col-xs-6 col-sm-3">
				<p><a class="btn btn-block btn-lg btn-primary icn-size-16 js-staff-action" data-action="add">{{ $_icons['add'] }}</a></p>
			</div>
		</div>
	</div>

	<ul class="nav nav-tabs">
		<li class="active"><a href="#blocks" data-toggle="tab">Blocks</a></li>
		<li><a href="#schedule" data-toggle="tab">Regular Schedule</a></li>
	</ul>

	<div class="tab-content">
		<div id="blocks" class="tab-pane active">
			@if ($blocks->count() > 0)
				<div class="data-table data-table-striped data-table-bordered">
				@foreach ($blocks as $b)
					<div class="row">
						<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
							<p><strong>{{ $b->start->format(Config::get('bjga.dates.date')) }}</strong></p>
							<p class="text-muted text-sm">{{ $b->start->format(Config::get('bjga.dates.time')) }} - {{ $b->end->format(Config::get('bjga.dates.time')) }}</p>

							@if ( ! empty($b->notes))
								<p class="text-sm text-info">{{ $b->notes }}</p>
							@endif
						</div>
						<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
							<div class="visible-md visible-lg">
								<div class="btn-toolbar pull-right">
									<div class="btn-group">
										<a href="#" class="btn btn-sm btn-danger icn-size-16 js-staff-action js-tooltip-top" data-title="Remove Block" data-action="delete" data-id="{{ $b->id }}">{{ $_icons['remove'] }}</a>
									</div>
								</div>
							</div>
							<div class="visible-xs visible-sm">
								<div class="row">
									<div class="col-xs-12 col-sm-12">
										<p><a href="#" class="btn btn-lg btn-block btn-danger icn-size-16 js-staff-action" data-action="delete" data-id="{{ $b->id }}">Remove Block</a></p>
									</div>
								</div>
							</div>
						</div>
					</div>
				@endforeach
				</div>
			@else
				{{ partial('common/alert', array('content' => 'No upcoming schedule blocks found.')) }}
			@endif
		</div>

		<div id="schedule" class="tab-pane">
			<div class="row">
				<div class="col-lg-6">
					<div class="data-table data-table-striped data-table-bordered">
					@for ($d = 0; $d <=6; $d++)
						<div class="row">
							<div class="col-xs-6 col-sm-4 col-md-3 col-lg-4">
								<p><strong>{{ $days[$d] }}</strong></p>
							</div>
							<div class="col-xs-6 col-sm-4 col-md-3 col-lg-5">
								{{ $staff->present()->niceAvailability($d) }}
							</div>
							<div class="col-xs-12 col-sm-4 col-md-6 col-lg-3">
								<div class="visible-md visible-lg">
									<div class="btn-toolbar pull-right">
										<div class="btn-group">
											<a href="#" class="btn btn-sm btn-default icn-size-16 js-staff-action js-tooltip-top" data-title="Edit Day's Schedule" data-action="edit" data-id="{{ $staff->id }}" data-day="{{ $d }}">{{ $_icons['edit'] }}</a>
										</div>
									</div>
								</div>
								<div class="visible-xs visible-sm">
									<p><a href="#" class="btn btn-lg btn-block btn-default icn-size-16 js-staff-action" data-action="edit" data-id="{{ $staff->id }}" data-day="{{ $d }}">Edit Day's Schedule</a></p>
								</div>
							</div>
						</div>
					@endfor
					</div>
				</div>
			</div>
		</div>
	</div>
@stop

@section('modals')
	{{ modal(array('id' => 'addBlock', 'header' => "Create Schedule Block")) }}
	{{ modal(array('id' => 'deleteBlock', 'header' => "Remove Schedule Block")) }}
	{{ modal(array('id' => 'editSchedule', 'header' => "Edit Regular Schedule")) }}
@stop

@section('scripts')
	<script type="text/javascript">
		$('.js-staff-action').on('click', function(e)
		{
			e.preventDefault();

			var action = $(this).data('action');
			var id = $(this).data('id');

			if (action == 'add')
			{
				$('#addBlock').modal({
					remote: "{{ URL::route('admin.staff.block.create') }}"
				}).modal('show');
			}

			if (action == 'delete')
			{
				$('#deleteBlock').modal({
					remote: "{{ URL::to('admin/staff/schedule/block/delete') }}/" + id
				}).modal('show');
			}

			if (action == 'edit')
			{
				$('#editSchedule').modal({
					remote: "{{ URL::to('admin/staff/schedule/edit/staff') }}/" + id + "/day/" + $(this).data('day')
				}).modal('show');
			}
		});
	</script>
@stop