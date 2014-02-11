@extends('layouts.master')

@section('title')
	Staff
@endsection

@section('content')
	<h1>Staff</h1>

	<div class="visible-lg">
		<div class="btn-toolbar">
			<div class="btn-group">
				<a href="{{ URL::route('admin') }}" class="btn btn-sm btn-default icn-size-16">{{ $_icons['back'] }}</a>
			</div>
			<div class="btn-group">
				<a href="{{ URL::route('admin.staff.create') }}" class="btn btn-sm btn-primary icn-size-16">{{ $_icons['add'] }}</a>
			</div>
		</div>
	</div>
	<div class="hidden-lg">
		<div class="row">
			<div class="col-xs-6 col-sm-6">
				<p><a href="{{ URL::route('admin') }}" class="btn btn-block btn-lg btn-default icn-size-16">{{ $_icons['back'] }}</a></p>
			</div>
			<div class="col-xs-6 col-sm-6">
				<p><a href="{{ URL::route('admin.staff.create') }}" class="btn btn-block btn-lg btn-primary icn-size-16">{{ $_icons['add'] }}</a></p>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
			{{ Form::text('search', null, array('placeholder' => 'Search for staff', 'class' => 'form-control search-control', 'id' => 'searchStaff')) }}
		</div>
	</div>

	<div class="data-table data-table-striped data-table-bordered" id="staffTable">
	@foreach ($staff as $s)
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-lg-9">
				<p><strong>{{ $s->user->name }}</strong></p>
				<p class="text-muted text-small">{{ $s->user->email }}</p>
			</div>
			<div class="col-xs-12 col-sm-12 col-lg-3">
				<div class="visible-lg">
					<div class="btn-toolbar pull-right">
						<div class="btn-group">
							<a href="{{ URL::route('admin.staff.edit', array($s->id)) }}" class="btn btn-sm btn-default icn-size-16">{{ $_icons['edit'] }}</a>
						</div>
						<div class="btn-group">
							<a href="#" class="btn btn-sm btn-danger icn-size-16 js-staff-action" data-action="delete" data-id="{{ $s->id }}">{{ $_icons['remove'] }}</a>
						</div>
					</div>
				</div>
				<div class="hidden-lg">
					<div class="row">
						<div class="col-xs-6 col-sm-6">
							<p><a href="{{ URL::route('admin.staff.edit', array($s->id)) }}" class="btn btn-block btn-lg btn-default icn-size-16">{{ $_icons['edit'] }}</a></p>
						</div>
						<div class="col-xs-12 col-sm-12">
							<p><a href="#" class="btn btn-block btn-lg btn-danger icn-size-16 js-staff-action" data-action="delete" data-id="{{ $s->id }}">{{ $_icons['remove'] }}</a></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	@endforeach
	</div>

	{{ modal(array('id' => 'deleteStaff', 'header' => "Remove Staff Member")) }}
@endsection

@section('scripts')
	{{ HTML::script('js/jquery.quicksearch.min.js') }}
	<script>

		$('#searchStaff').quicksearch('#staffTable > div', {
			hide: function()
			{
				$(this).addClass('hide');
			},
			show: function()
			{
				$(this).removeClass('hide');
			}
		});
		
		$('.js-staff-action').on('click', function(e)
		{
			e.preventDefault();

			var action = $(this).data('action');
			var id = $(this).data('id');

			if (action == 'delete')
			{
				$('#deleteStaff').modal({
					remote: "{{ URL::to('ajax/staff/delete') }}/" + id
				}).modal('show');
			}
		});

	</script>
@endsection