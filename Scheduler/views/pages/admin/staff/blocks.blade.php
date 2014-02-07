@extends('layouts.master')

@section('title')
	Schedule Blocking
@endsection

@section('content')
	<h1>Schedule Blocking</h1>

	<div class="visible-lg">
		<div class="btn-toolbar">
			<div class="btn-group">
				<a href="{{ URL::route('admin') }}" class="btn btn-default icn-size-16">{{ $_icons['back'] }}</a>
			</div>
			<div class="btn-group">
				<a class="btn btn-primary icn-size-16 js-staff-action" data-action="add">{{ $_icons['add'] }}</a>
			</div>
		</div>
	</div>
	<div class="hidden-lg">
		<div class="row">
			<div class="col-xs-6 col-sm-6">
				<p><a href="{{ URL::route('admin') }}" class="btn btn-block btn-lg btn-default icn-size-16">{{ $_icons['back'] }}</a></p>
			</div>
			<div class="col-xs-6 col-sm-6">
				<p><a class="btn btn-block btn-lg btn-primary icn-size-16 js-staff-action" data-action="add">{{ $_icons['add'] }}</a></p>
			</div>
		</div>
	</div>

	@if ($blocks->count() > 0)
		<div class="data-table data-table-striped data-table-bordered">
		@foreach ($blocks as $b)
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-lg-9">
					<p><strong>{{ $b->start->format('l F jS, Y') }}</strong></p>
					<p class="text-muted text-small">{{ $b->start->format('g:ia') }} - {{ $b->end->format('g:ia') }}</p>
				</div>
				<div class="col-xs-12 col-sm-12 col-lg-3">
					<div class="visible-lg">
						<div class="btn-toolbar pull-right">
							<div class="btn-group">
								<a href="#" class="btn btn-small btn-danger icn-size-16 js-staff-action" data-action="delete" data-id="{{ $b->id }}">{{ $_icons['remove'] }}</a>
							</div>
						</div>
					</div>
					<div class="hidden-lg">
						<div class="row">
							<div class="col-xs-12 col-sm-12">
								<p><a href="#" class="btn btn-block btn-lg btn-danger icn-size-16 js-staff-action" data-action="delete" data-id="{{ $b->id }}">{{ $_icons['remove'] }}</a></p>
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

	{{ modal(array('id' => 'addBlock', 'header' => "Create Schedule Block")) }}
	{{ modal(array('id' => 'deleteBlock', 'header' => "Remove Schedule Block")) }}
@endsection

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
					remote: "{{ URL::route('admin.staff.block.create') }}"
				}).modal('show');
			}
		});

	</script>
@endsection