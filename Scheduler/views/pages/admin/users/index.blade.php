@extends('layouts.master')

@section('title')
	Users
@stop

@section('content')
	<h1>Users</h1>

	@if ($_currentUser->access() > 2)
		<div class="visible-md visible-lg">
			<div class="btn-toolbar">
				<div class="btn-group">
					<a href="{{ URL::route('admin.user.create') }}" class="btn btn-sm btn-primary icn-size-16">{{ $_icons['add'] }}</a>
				</div>
			</div>
		</div>
		<div class="visible-xs visible-sm">
			<div class="row">
				<div class="col-xs-6 col-sm-3">
					<p><a href="{{ URL::route('admin.user.create') }}" class="btn btn-block btn-lg btn-primary icn-size-16">{{ $_icons['add'] }}</a></p>
				</div>
			</div>
		</div>
	@endif

	<div class="row">
		<div class="col-sm-6 col-md-6 col-lg-4">
			{{ Form::text('search', null, array('placeholder' => 'Search for users', 'class' => 'form-control search-control', 'id' => 'searchUsers')) }}
		</div>
	</div>

	<div class="data-table data-table-striped data-table-bordered" id="usersTable">
	@foreach ($users as $user)
		<div class="row">
			<div class="col-sm-6 col-md-6 col-lg-8">
				<p><strong>{{ $user->name }}</strong></p>
				<p class="text-muted text-sm">{{ $user->email }}</p>
			</div>
			<div class="col-sm-6 col-md-6 col-lg-4">
				<div class="visible-md visible-lg">
					<div class="btn-toolbar pull-right">
						@if ( ! empty($user->email))
							<div class="btn-group">
								<a href="#" class="btn btn-sm btn-default icn-size-16 js-email js-tooltip-top" data-title="Email Student" data-user="{{ $user->id }}">{{ $_icons['email'] }}</a>
							</div>
						@endif

						<div class="btn-group">
							<a href="{{ URL::route('admin.user.edit', array($user->id)) }}" class="btn btn-sm btn-default icn-size-16 js-tooltip-top" data-title="Edit Student">{{ $_icons['edit'] }}</a>
						</div>

						@if ($_currentUser->access() > 2)
							<div class="btn-group">
								<a href="{{ URL::route('admin.user.destroy', array($user->id)) }}" class="btn btn-sm btn-danger icn-size-16 js-user-action js-tooltip-top" data-title="Remove Student" data-action="delete" data-id="{{ $user->id }}">{{ $_icons['remove'] }}</a>
							</div>
						@endif
					</div>
				</div>
				<div class="visible-xs visible-sm">
					<div class="row">
						@if ( ! empty($user->phone))
							<div class="visible-xs col-xs-12">
								<p><a href="tel:{{ $user->phone }}" class="btn btn-block btn-lg btn-default icn-size-16">Call Student</a></p>
							</div>
						@endif

						@if ( ! empty($user->email))
							<div class="visible-xs col-xs-12">
								<p><a href="tel:{{ $user->email }}" class="btn btn-block btn-lg btn-default icn-size-16">Email Student</a></p>
							</div>
						@endif

						<div class="col-xs-12 col-sm-6">
							<p><a href="{{ URL::route('admin.user.edit', array($user->id)) }}" class="btn btn-block btn-lg btn-default icn-size-16">Edit Student</a></p>
						</div>

						@if ($_currentUser->access() > 2)
							<div class="col-xs-12 col-sm-6">
								<p><a href="{{ URL::route('admin.user.destroy', array($user->id)) }}" class="btn btn-block btn-lg btn-danger icn-size-16 js-user-action" data-action="delete" data-id="{{ $user->id }}">Remove Student</a></p>
							</div>
						@endif
					</div>
				</div>
			</div>
		</div>
	@endforeach
	</div>
@stop

@section('modals')
	{{ modal(array('id' => 'deleteUser', 'header' => "Delete User")) }}
	{{ modal(array('id' => 'emailUser', 'header' => "Email User")) }}
@stop

@section('scripts')
	{{ HTML::script('js/jquery.quicksearch.min.js') }}
	<script>

		$('#searchUsers').quicksearch('#usersTable > div', {
			hide: function()
			{
				$(this).addClass('hide');
			},
			show: function()
			{
				$(this).removeClass('hide');
			}
		});
		
		$('.js-user-action').on('click', function(e)
		{
			e.preventDefault();

			var action = $(this).data('action');
			var id = $(this).data('id');

			if (action == 'delete')
			{
				$('#deleteUser').modal({
					remote: "{{ URL::to('ajax/user/delete') }}/" + id
				}).modal('show');
			}
		});

		$('.js-email').on('click', function(e)
		{
			e.preventDefault();

			var id = $(this).data('user');

			$('#emailUser').modal({
				remote: "{{ URL::to('ajax/user/email/user') }}/" + id
			}).modal('show');
		});

	</script>
@stop